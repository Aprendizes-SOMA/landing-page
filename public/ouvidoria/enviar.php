<?php
/**
 * Ouvidoria SOMA: recebe o formulário e envia por e-mail.
 *
 * Requisitos: PHP >= 7.4 com mbstring. Sem dependências externas.
 *
 * - Só aceita POST (outros métodos: 405).
 * - Destinatários e remetente são FIXOS. Nada do formulário vai para cabeçalhos.
 * - Não registra IP nem user-agent. O limite de envios usa apenas um HMAC do IP.
 * - Responde com POST-Redirect-GET para ./?status=ok|erro.
 *
 * Modo de teste (somente local): se a variável de ambiente do processo
 * OUVIDORIA_TESTE=1 existir, grava o e-mail como .eml em /tmp/ouvidoria-teste/
 * em vez de enviar. Nada vindo da requisição consegue ativar esse modo.
 */

declare(strict_types=1);

// Não exibe erros ao visitante mesmo se o servidor ignorar o .user.ini.
ini_set('display_errors', '0');

// ===== CONFIGURAÇÃO =====
const DESTINATARIOS = ['gerencia.adm@somapiaui.com.br', 'rh@somapiaui.com.br'];
const REMETENTE     = 'site@somapiaui.com.br';
const NOME_REMETENTE = 'Ouvidoria Site SOMA';
const PASTA_DADOS   = '/home/somapiauic/ouvidoria-data';
const ESTADOS       = ['PI' => 'Piauí', 'MA' => 'Maranhão', 'PA' => 'Pará', 'CE' => 'Ceará', 'Outro' => 'Outro'];
const MSG_MIN = 20;
const MSG_MAX = 5000;
const LOCAL_MAX = 300;
const LIMITE_JANELA_CURTA = 600;    // 10 minutos
const LIMITE_MAX_CURTA    = 3;
const LIMITE_JANELA_LONGA = 86400;  // 24 horas
const LIMITE_MAX_LONGA    = 10;
// ========================

// getenv(..., true) lê só o ambiente do processo PHP: cabeçalhos HTTP e
// variáveis repassadas pelo servidor web não chegam aqui.
define('MODO_TESTE', getenv('OUVIDORIA_TESTE', true) === '1');
const PASTA_TESTE = '/tmp/ouvidoria-teste';

date_default_timezone_set('America/Fortaleza');
mb_internal_encoding('UTF-8');

function redirecionar(array $params): void
{
    header('Cache-Control: no-store');
    $ancora = ($params['status'] ?? '') === 'ok' ? '#enviado' : '#erro';
    header('Location: ./?' . http_build_query($params) . $ancora, true, 303);
    exit;
}

function erro(string $motivo): void
{
    redirecionar(['status' => 'erro', 'motivo' => $motivo]);
}

function novo_protocolo(): string
{
    return date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
}

/**
 * Lê um campo de texto do POST. Retorna null se não for texto UTF-8 válido.
 * Normaliza quebras de linha e remove caracteres de controle (exceto \n).
 */
function campo(string $nome): ?string
{
    $v = $_POST[$nome] ?? '';
    if (!is_string($v)) {
        return null;
    }
    if (!mb_check_encoding($v, 'UTF-8')) {
        return null;
    }
    $v = str_replace(["\r\n", "\r"], "\n", $v);
    $v = preg_replace('/[\x00-\x09\x0B-\x1F\x7F\x{80}-\x{9F}\x{2028}\x{2029}]/u', '', $v);
    if ($v === null) {
        return null;
    }
    return trim($v);
}

/** Pasta de dados do limite de envios, ou null se indisponível. */
function pasta_dados(): ?string
{
    $pasta = MODO_TESTE ? PASTA_TESTE . '/data' : PASTA_DADOS;
    if (MODO_TESTE && !is_dir($pasta)) {
        @mkdir($pasta, 0700, true);
    }
    if (!is_dir($pasta) || !is_writable($pasta)) {
        error_log('ouvidoria: pasta de dados indisponível; limite de envios desativado');
        return null;
    }
    return $pasta;
}

/** Sal secreto do HMAC: gerado e salvo na primeira execução. */
function obter_sal(string $pasta): ?string
{
    $arquivo = $pasta . '/sal.key';
    $fp = @fopen($arquivo, 'c+');
    if ($fp === false) {
        return null;
    }
    $sal = null;
    if (flock($fp, LOCK_EX)) {
        $conteudo = stream_get_contents($fp);
        if (is_string($conteudo) && strlen($conteudo) === 64 && ctype_xdigit($conteudo)) {
            $sal = $conteudo;
        } else {
            $sal = bin2hex(random_bytes(32));
            ftruncate($fp, 0);
            rewind($fp);
            fwrite($fp, $sal);
            fflush($fp);
        }
        flock($fp, LOCK_UN);
    }
    fclose($fp);
    @chmod($arquivo, 0600);
    return $sal;
}

/**
 * Registra a tentativa e diz se ela está dentro do limite.
 * Retorna true (permitido) também quando o controle não está disponível.
 */
function dentro_do_limite(): bool
{
    $pasta = pasta_dados();
    if ($pasta === null) {
        return true;
    }
    $sal = obter_sal($pasta);
    if ($sal === null) {
        error_log('ouvidoria: não foi possível ler o sal; limite de envios desativado');
        return true;
    }
    $ip = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    $chave = hash_hmac('sha256', $ip, $sal);

    $arquivo = $pasta . '/limites.json';
    $fp = @fopen($arquivo, 'c+');
    if ($fp === false) {
        error_log('ouvidoria: não foi possível abrir o controle de limite');
        return true;
    }
    $permitido = true;
    if (flock($fp, LOCK_EX)) {
        $dados = json_decode((string)stream_get_contents($fp), true);
        if (!is_array($dados)) {
            $dados = [];
        }
        $agora = time();
        // Remove registros com mais de 24 h (de todos os IPs).
        foreach ($dados as $k => $lista) {
            $lista = array_values(array_filter((array)$lista, static function ($t) use ($agora) {
                return is_int($t) && $t > $agora - LIMITE_JANELA_LONGA;
            }));
            if ($lista) {
                $dados[$k] = $lista;
            } else {
                unset($dados[$k]);
            }
        }
        $meus = $dados[$chave] ?? [];
        $recentes = count(array_filter($meus, static function ($t) use ($agora) {
            return $t > $agora - LIMITE_JANELA_CURTA;
        }));
        if ($recentes >= LIMITE_MAX_CURTA || count($meus) >= LIMITE_MAX_LONGA) {
            $permitido = false;
        } else {
            $meus[] = $agora;
            $dados[$chave] = $meus;
        }
        ftruncate($fp, 0);
        rewind($fp);
        fwrite($fp, json_encode($dados));
        fflush($fp);
        flock($fp, LOCK_UN);
    }
    fclose($fp);
    @chmod($arquivo, 0600);
    return $permitido;
}

function enviar_email(string $protocolo, string $estado, string $local, string $mensagem): bool
{
    $para = implode(', ', DESTINATARIOS);
    $assunto = mb_encode_mimeheader("[Ouvidoria] Nova manifestação – Protocolo $protocolo", 'UTF-8', 'B', "\r\n");

    $corpo  = "NOVA MANIFESTAÇÃO DA OUVIDORIA\n";
    $corpo .= "==============================\n\n";
    $corpo .= "Protocolo: $protocolo\n";
    $corpo .= 'Data/hora: ' . date('d/m/Y H:i') . " (horário de Fortaleza)\n";
    $corpo .= 'Estado:    ' . ($estado !== '' ? ESTADOS[$estado] : 'Não informado') . "\n";
    $corpo .= 'Cidade / área de atuação: ' . ($local !== '' ? $local : 'Não informado') . "\n\n";
    $corpo .= "Mensagem:\n---------\n$mensagem\n\n";
    $corpo .= "--\nEnviado pelo formulário de Ouvidoria do site. IP e navegador não são registrados.\n";
    // base64 mantém as linhas curtas (servidores recusam linhas > 998 bytes).
    $corpo64 = chunk_split(base64_encode(str_replace("\n", "\r\n", $corpo)), 76, "\r\n");

    $cabecalhos = [
        'From: ' . NOME_REMETENTE . ' <' . REMETENTE . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64',
        'Auto-Submitted: auto-generated',
    ];

    if (MODO_TESTE) {
        $pasta = PASTA_TESTE;
        if (!is_dir($pasta)) {
            @mkdir($pasta, 0700, true);
        }
        $eml = "To: $para\r\nSubject: $assunto\r\nDate: " . date(DATE_RFC2822) . "\r\n"
             . implode("\r\n", $cabecalhos) . "\r\n\r\n" . $corpo64;
        return @file_put_contents("$pasta/$protocolo.eml", $eml) !== false;
    }

    // -f alinha o remetente do envelope ao domínio (SPF/DMARC).
    return mail($para, $assunto, $corpo64, implode("\r\n", $cabecalhos), '-f' . REMETENTE);
}

// ===== Fluxo principal =====

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><html lang="pt-BR"><meta charset="utf-8"><title>Método não permitido</title>'
       . '<p>Método não permitido. <a href="./">Voltar à Ouvidoria</a></p></html>';
    exit;
}

// Recusa envios grandes antes de ler os campos (o formulário legítimo tem poucos KB).
if ((int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 65536) {
    erro('longa');
}

// Armadilha anti-robô: finge sucesso e não envia nada.
$armadilha = $_POST['site'] ?? '';
if (!is_string($armadilha) || trim($armadilha) !== '') {
    redirecionar(['status' => 'ok', 'protocolo' => novo_protocolo()]);
}

$mensagem = campo('mensagem');
$local    = campo('local');
$estado   = $_POST['estado'] ?? '';

if ($mensagem === null || $local === null || !is_string($estado)) {
    erro('invalido');
}
if ($mensagem === '') {
    erro('vazia');
}
if (mb_strlen($mensagem) < MSG_MIN) {
    erro('curta');
}
if (mb_strlen($mensagem) > MSG_MAX) {
    erro('longa');
}
if (mb_strlen($local) > LOCAL_MAX) {
    erro('local');
}
if ($estado !== '' && !array_key_exists($estado, ESTADOS)) {
    erro('estado');
}

if (!dentro_do_limite()) {
    erro('limite');
}

$protocolo = novo_protocolo();
if (!enviar_email($protocolo, $estado, $local, $mensagem)) {
    error_log("ouvidoria: falha ao enviar a manifestação $protocolo");
    erro('falha');
}

redirecionar(['status' => 'ok', 'protocolo' => $protocolo]);
