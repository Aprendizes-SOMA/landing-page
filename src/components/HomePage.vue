<template>
  <div class="home">
    <div class="contentWrapper">
      <div class="contentContainer">
        <h2 class="textHome">
          Construímos com propósito, <br /> crescemos com responsabilidade.
        </h2>
        <div class="logoContainer">
          <img loading="lazy" :src="imageSrc" alt="Logo Adicional" class="logo" />
        </div>
      </div>

      <div class="videoWrapper">
        <iframe src="https://www.youtube.com/embed/JvXzxdRNqQM?rel=0&modestbranding=1&showinfo=0"
          title="SOMA Institucional" frameborder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowfullscreen>
        </iframe>
      </div>



    </div>
  </div>
</template>

<script>
import imageDesktop from "@/assets/images/image.svg";
import imageMobile from "@/assets/images/image.svg";

export default {
  name: "HomePage",
  data() {
    return {
      imageSrc: window.innerWidth <= 768 ? imageMobile : imageDesktop,
    };
  },
  mounted() {
    window.addEventListener("resize", this.checkScreenSize);
  },
  beforeUnmount() {
    window.removeEventListener("resize", this.checkScreenSize);
  },
  methods: {
    checkScreenSize() {
      if (window.innerWidth <= 768) {
        this.imageSrc = imageMobile;
      } else {
        this.imageSrc = imageDesktop;
      }
    },
  },
};
</script>

<style scoped>
.home {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  padding: 0 10px;
  background-size: cover;
  background-position: center;
}

.contentWrapper {
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-direction: column;
  gap: 20px;
  width: 100%;
  margin-top: -100px;
}

.contentContainer {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.textHome {
  color: white;
  font-size: 1.5rem;
  font-weight: bold;
  text-transform: uppercase;
  margin-bottom: 20px;
  font-family: 'CustomFont', Arial, sans-serif;
  text-align: center;
}

.logoContainer {
  display: flex;
  justify-content: center;
}

.logo {
  width: 300px;
  height: auto;
}


.videoWrapper {
  position: relative;
  width: 100%;
  max-width: 674px;
  margin: 0 auto;
  padding-bottom: 25%;
  height: 0;
  overflow: hidden;
}

.videoWrapper iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border: none;
}


@media (max-width: 768px) {
  .contentWrapper {
    flex-direction: column;
    gap: 20px;
  }

  .textHome {
    font-size: 1.1rem;
  }

  .logo {
    width: 200px;
  }

  .videoWrapper {
    width: 50%;
    padding-bottom: 30%;
  }
}

@media (min-width: 8820px) and (max-width: 1180px) {
.videoWrapper {
    width: 70%;
    padding-bottom: 50%;
  }
}
</style>
