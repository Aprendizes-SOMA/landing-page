<template>
  <section class="client-area-wrapper">
    <p class="client-area-description">
      Emita a segunda via do seu boleto com CPF ou CNPJ<br />
      e mantenha seus pagamentos em dia.
    </p>
    <a 
      class="client-area-button" 
      href="https://somapiaui.com.br/segunda-via/" 
      target="_blank" 
      rel="noopener noreferrer"
    >
      Acessar
    </a>
  </section>
</template>

<script>
export default {
  name: "ClientArea",
};
</script>

<style scoped>
.client-area-wrapper {
  text-align: center;
  color: white;
  padding: 30px 10px;
  background-color: #1a1a1a;
  border-radius: 12px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
  margin: 40px auto;
  max-width: 700px;
  width: 100%;
}

.client-area-description {
  font-size: 1rem;
  line-height: 1.6;
  margin-bottom: 30px;
  word-break: break-word;
  overflow-wrap: break-word;
}

.client-area-button {
  background-color: #fd8700;
  color: white;
  padding: 10px 20px;
  font-size: 0.9rem;
  text-decoration: none;
  border-radius: 6px;
  transition: background-color 0.3s ease, transform 0.2s ease;
  display: inline-block;
  max-width: 110px;
  width: 100%;
  margin: 0 auto;
  text-align: center;
}

.client-area-button:hover {
  background-color: #e07700;
  transform: translateY(-2px);
}

@media (max-width: 768px) {
  .client-area-wrapper {
    max-width: 95%; 
    margin: 20px 3.5%; 
    padding: 10px 10px; 
    width: 50%;
  }


  .client-area-description {
    font-size: 0.9rem;
    text-align: center;
    margin-bottom: 20px;
    font-size: 0.8rem;
  }

  .client-area-button {
    padding: 10px 0;
    font-size: 0.80rem;
    max-width: 70px;
  }
}
</style>
