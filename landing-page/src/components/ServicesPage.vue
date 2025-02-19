<template>
  <div class="services-page">
    <nav class="services-tabs">
      <button 
        v-for="(tab, index) in tabs" 
        :key="index" 
        :class="['tab-button', { active: activeTab === tab.name }, tab.name]" 
        @click="activeTab = tab.name">
        {{ tab.title }}
      </button>
    </nav>
    <section class="services-content">
      <div 
        v-for="(tab, index) in tabs" 
        :key="index" 
        class="tab-content" 
        :class="{ active: activeTab === tab.name }">
        <div class="content-container">
          <img 
            :src="tab.name === 'pavimentacao' ? tab.images[0] : tab.image" 
            :alt="tab.alt" 
            :class="['single-image', { 'adutora-image': tab.name === 'adutora' }]" />
          <div class="description">
            <p class="text">{{ tab.description }}</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "ServicesPage",
  data() {
    return {
      activeTab: "pavimentacao",
      tabs: [
        {
          name: "pavimentacao",
          title: "Pavimentação",
          images: [
            require("../../public/images/pavimentacao.svg"),
            require("@/assets/images/img1.webp"),
            require("@/assets/images/img2.webp"),
          ],
          alt: "Imagem representando pavimentação",
          description:
            "Oferecemos serviços de pavimentação utilizando materiais de alta durabilidade e técnicas avançadas, proporcionando superfícies lisas, seguras e de longa vida útil para estradas e calçadas.",
        },
        {
          name: "habitacional",
          title: "Construção Habitacional",
          image: require("@/assets/images/conthab.webp"),
          alt: "Imagem representando construção habitacional",
          description:
            "Transformamos sonhos em realidade por meio de projetos personalizados, construindo residências seguras, confortáveis e duradouras com materiais de alta qualidade.",
        },
        {
          name: "adutora",
          title: "Adutora",
          image: require("@/assets/images/adutora.webp"),
          alt: "Imagem representando adutora",
          description:
            "Realizamos a instalação de adutoras com excelência, assegurando o transporte eficiente e seguro de água, em total conformidade com as normas técnicas e ambientais.",
        },
      ],
    };
  },
};
</script>



<style scoped>
.services-page {
  color: #ffffff;
  max-width: 1090px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  overflow: auto;
  font-family: 'Arial', sans-serif;
}

.services-tabs {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem; /* Ajuste para aumentar o espaçamento */
  padding: 1rem 0;
  gap: 10px; /* Espaçamento entre os botões */
  flex-wrap: wrap; /* Permite que os botões quebrem para a próxima linha */
}

.tab-button {
  background: none;
  border: none;
  color: #999999;
  font-size: 1.2rem;
  padding: 0.5rem 1rem;
  cursor: pointer;
  font-weight: bold;
  transition: color 0.3s ease, font-weight 0.3s ease;
  position: relative; 
  white-space: nowrap; 
  min-width: 120px; /* Largura mínima para evitar que os botões fiquem muito estreitos */
  text-align: center; /* Centraliza o texto dentro do botão */
}

/* Efeito de hover para todos os botões */
.tab-button:hover {
  color: orange; /* Altera a cor do texto para laranja no hover */
}

.tab-button.active {
  color: #ffffff;
  font-weight: bold;
}

.tab-button.active::after {
  content: "";
  position: absolute;
  bottom: -5px;
  left: 50%; 
  transform: translateX(-50%); 
  width: 90%;
  height: 3px;
  background-color: orange;
  border-radius: 2px;
}


@media (max-width: 768px) {
  .tab-button.habitacional {
    white-space: normal; 
    line-height: 1.2; 
    font-size: 0.9rem; 
    padding: 0.4rem 0.6rem; 
  }

  
  .tab-button.habitacional:hover {
    color: orange; 
  }
}

@media (max-width: 768px) {
  .tab-button {
    font-size: 1rem; 
    padding: 0.4rem 0.8rem; 
  }

  .tab-button.active::after {
    width: 100%; 
    height: 2px; 
  }
}

@media (max-width: 480px) {
  .tab-button {
    font-size: 0.8rem; 
    padding: 0.3rem 0.6rem; 
  }

  .services-tabs {
    gap: 5px; 
  }
}

.services-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: left;
  width: 100%;
}

.tab-content {
  display: flex;
  flex-direction: column;
  gap: 20px;
  align-items: center;
  height: auto;
  width: 100%;
  padding: 0;
  opacity: 0;
  transform: translateX(-10px);
  transition: opacity 0.7s ease, transform 0.7s ease;
  position: absolute;
  top: 0;
  left: 0;
}

.tab-content.active {
  opacity: 1;
  transform: translateX(0);
  position: relative;
}

.content-container {
  display: flex;
  align-items: flex-start;
  gap: 20px;
  width: 100%;
  margin-top: 20px; /* Adiciona mais espaço acima do conteúdo */
}

.single-image {
  max-width: 400px;
  height: auto;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.adutora-image {
  max-width: 400px;
}

.description {
  flex: 1;
  text-align: left;
  margin-top: -10px;
}

.text {
  color: white;
  font-size: 1.07rem;
  line-height: 1.6;
  margin: 1%;
}

@media (max-width: 1024px) {
  .content-container {
    flex-direction: column;
    align-items: center;
  }

  .single-image,
  .adutora-image {
    max-width: 50%;
  }

  .content-container {
    flex-direction: column;
    align-items: center;
    text-align: center; /* Para centralizar os textos */
  }

  .description {
    text-align: center; /* Centraliza o texto */
  }

  .services-tabs {
    flex-wrap: wrap;
    justify-content: center;
  }

  .tab-button {
    flex: 0.1%;
    min-width: 80px;
  }
}

.description {
  flex: 1;
  text-align: center; /* Centraliza no mobile */
  max-width: 50%; /* Evita que fique muito estreito */
  padding: 15px; /* Adiciona espaço interno */
}

.text {
  font-size: 1rem; /* Aumenta o tamanho do texto */
  line-height: 1.3; /* Dá mais espaçamento entre as linhas */
  text-align: justify; /* Justifica o texto para melhor leitura */
  margin: 2px auto; /* Centraliza e adiciona espaçamento */
}
</style>