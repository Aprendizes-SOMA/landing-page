<?php
// ===== CONFIGURAÇÃO =====
$PARA      = 'rh@somapiaui.com.br';   // caixa que recebe as manifestações
$REMETENTE = 'site@somapiaui.com.br';        // precisa ser do domínio da empresa
// ========================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ./');
  exit;
}

date_default_timezone_set('America/Fortaleza');

// Lê um campo de texto do POST (ignora arrays e normaliza quebras de linha)
function campo($nome) {
  $v = $_POST[$nome] ?? '';
  if (!is_string($v)) return '';
  return trim(str_replace("\r\n", "\n", $v));
}

$ok = false;
$protocolo = '';

if (campo('site') !== '') {
  $ok = true; // robô preencheu o campo escondido: finge sucesso e ignora
} else {
  $mensagem = campo('mensagem');
  $local    = campo('local');

  if ($mensagem !== '' && mb_strlen($mensagem) <= 5000 && mb_strlen($local) <= 300) {
    $protocolo = date('Ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));

    $assunto = '=?UTF-8?B?' . base64_encode("Ouvidoria - Protocolo $protocolo") . '?=';

    $corpo  = "NOVA MANIFESTAÇÃO DA OUVIDORIA\n";
    $corpo .= "==============================\n\n";
    $corpo .= "Protocolo: $protocolo\n";
    $corpo .= "Data:      " . date('d/m/Y H:i') . "\n";
    $corpo .= "Cidade / área de atuação: " . ($local !== '' ? $local : 'Não informado') . "\n\n";
    $corpo .= "Mensagem:\n---------\n$mensagem\n";

    $headers  = "From: Ouvidoria Site <$REMETENTE>\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";

    // base64 mantém as linhas curtas (servidores recusam linhas > 998 bytes)
    // -f alinha o remetente técnico ao domínio (evita cair no spam)
    $ok = mail($PARA, $assunto, chunk_split(base64_encode($corpo)), $headers, '-f' . $REMETENTE);
  }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Ouvidoria - Construtora SOMA</title>
  <link rel="icon" href="../favicon.ico">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body { margin:0; min-height:100vh; background:#1f1f1f; color:#fff;
           font-family:'Poppins',Arial,sans-serif; display:flex;
           justify-content:center; align-items:center; padding:16px; text-align:center; }
    .caixa { max-width:500px; background:#2a2a2a; border-radius:12px; padding:40px 24px; }
    h2 { color:#FD8700; margin-top:0; }
    .protocolo { font-size:1.2rem; font-weight:bold; letter-spacing:1px; }
    a { display:inline-block; margin-top:20px; color:#FD8700; text-decoration:none; }
  </style>
</head>
<body>
  <div class="caixa">
    <?php if ($ok): ?>
      <h2>Manifestação enviada!</h2>
      <p>Obrigado por contar conosco. Sua mensagem foi recebida com sigilo.</p>
      <?php if ($protocolo !== ''): ?>
        <p>Protocolo de referência:</p>
        <p class="protocolo"><?= htmlspecialchars($protocolo) ?></p>
      <?php endif; ?>
    <?php else: ?>
      <h2>Não foi possível enviar</h2>
      <p>Verifique se escreveu a mensagem e tente novamente.</p>
      <a href="javascript:history.back()">← Voltar e corrigir</a><br>
    <?php endif; ?>
    <a href="../">← Voltar ao site</a>
  </div>
</body>
</html>
