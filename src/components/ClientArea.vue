<template>
    <section class="client-area-wrapper">
      <p class="client-area-description">
        Nesta área exclusiva, você pode emitir a segunda via do seu boleto de obra informando seu CPF ou CNPJ. Acesse com segurança e mantenha seus pagamentos em dia.
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
    padding: 40px 20px;
    background-color: #1a1a1a;
    border-radius: 12px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
    margin: 40px auto;
    max-width: 800px;
  }
  
  .client-area-title {
    font-size: 2rem;
    margin-bottom: 20px;
    color: #fd8700;
  }
  
  .client-area-description {
    font-size: 1.2rem;
    line-height: 1.6;
    margin-bottom: 30px;
  }
  
  .client-area-button {
    background-color: #fd8700;
    color: white;
    padding: 12px 30px;
    font-size: 1rem;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease, transform 0.2s ease;
    display: inline-block;
  }
  
  .client-area-button:hover {
    background-color: #e07700;
    transform: translateY(-2px);
  }
  </style>
  