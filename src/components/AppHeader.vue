<template>
  <header class="header">
    <div class="container">
      <div class="logo">
        <svg class="svg-logo" version="1.1" xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 400" xml:space="preserve">
          <path class="color1" d="M241.74,123.57l-12.09,39.87c0,0-19.51-8.4-38.34-3.68c0,0-13.32,4.79-7.74,13.98c5.57,9.19,39.28,11.45,39.28,11.45
                s-26.47,31.45-29.45,37.36c0,0-42.55-5.87-54.3-22.55c0,0-6.21-6.21-9.02-21.62c0,0-4.43-34.38,25.62-49.28
                S216.12,117.45,241.74,123.57z" />
          <path class="color1" d="M165.26,272.46c0,0,18.05-35.49,20.81-37.39c0,0-22.65-4.1-35.74-13.71l-17.85,38.47
                C132.47,259.83,146.85,269.3,165.26,272.46z" />
          <polygon class="color1" points="275.64,277.02 346.17,117.96 398.51,117.96 468.48,276.78 414.1,276.77 372.42,169.88 370.64,169.87 370.24,169.88 
                329.49,277.02" />
          <polygon class="color2" points="372.32,194.02 334.37,298.98 410.78,299.11" />
          <path class="color2"
            d="M291.67,124.34c0,0-82.31,47.62-130.95,157.21c0,0,12.09,2.74,17.83,2.53S179.68,240,291.67,124.34z" />
          <polygon class="color2" points="317.7,109.45 289.45,136.43 294.3,139.32" />
          <polygon class="color2" points="286.37,139.32 268.37,159.51 276.26,163.76 291.67,142.34" />
          <polygon class="color2" points="264.72,164.38 242.68,192.64 254.3,199.32 273.57,168.6" />
          <path class="color2"
            d="M239.36,198.64c0,0-19.36,29.28-20.77,33.62l16.55,5.13c0,0,10.53-25.85,16.66-32.74L239.36,198.64z" />
          <path class="color2"
            d="M312.83,124.38c0,0-36.49,61.83-50.96,133.15c0,0-8.34,12.94-21.62,18.04S281.62,157.87,312.83,124.38z" />
          <path class="color2" d="M216.13,238.53c0,0-17.11,31.47-18.38,46.21c0,0,13.72,1.09,22.66-2.23c0,0,1.53-19.79,12.57-38.49
                L216.13,238.53z" />
        </svg>
      </div>
      <button
        class="hamburger"
        @click="toggleMenu"
        :aria-label="menuOpen ? 'Fechar menu' : 'Abrir menu'"
      >
        <span></span>
        <span></span>
        <span></span>
      </button>
      <nav class="nav" :class="{ show: menuOpen }">
        <ul>
          <li><a href="#home">Início</a></li>
          <li class="dropdown">
            <a href="#about">Sobre</a>
            <ul class="dropdown-menu">
              <li><a href="#about">Sobre</a></li>
              <li><a href="#strategy">Identidade Estratégica</a></li>
            </ul>
          </li>
          <li><a href="#services">Serviços</a></li>
          <li><a href="#mainworks">Principais Obras</a></li>
          <li><a href="#">Verificação</a></li>
          <li><a href="#client-area">Área do Cliente</a></li>
          <li class="highlight"><a href="mailto:recursos.humanos@somapiaui.com.br">Trabalhe Conosco</a></li>
        </ul>
      </nav>
    </div>
  </header>
</template>

<script>
export default {
  name: "AppHeader",
  data() {
    return {
      menuOpen: false,
    };
  },
  methods: {
    toggleMenu() {
      this.menuOpen = !this.menuOpen;
    },
    closeMenuOnScroll() {
      if (this.menuOpen) {
        this.menuOpen = false;
      }
    },
  },
  mounted() {
    window.addEventListener("scroll", this.closeMenuOnScroll);
  },
  beforeUnmount() {
    window.removeEventListener("scroll", this.closeMenuOnScroll);
  },
};
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2% 0 0.5%;
  background-color: #333333;
  color: white;
  height: 100px;
  min-height: 80px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0;
  margin: 0;
  width: 1200px;
}

.header .container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo svg {
  height: 75px;
  width: auto;
}

.nav ul {
  display: flex;
  gap: 1.5rem;
  list-style: none;
}

.nav ul li {
  position: relative;
}

.nav ul li a {
  color: white;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.3s;
}

.nav ul li a:hover {
  color: #FD8700;
}

.nav ul li.highlight a {
  padding: 0.7rem 0.7rem;
  border: 2px solid #FD8700;
  border-radius: 5px;
  font-weight: bold;
  color: #FD8700;
}

.nav ul li.highlight a:hover {
  background-color: #FD8700;
  color: white;
  transition: 0.5s;
}

.nav ul li.dropdown {
  position: relative;
}

.nav ul li.dropdown .dropdown-menu {
  display: none;
  position: absolute;
  top: 100%;
  left: 0;
  background-color: #333;
  padding: 0.5rem 1rem;
  border-radius: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.nav ul li.dropdown .dropdown-menu li {
  margin: 0;
  padding: 0;
}

.nav ul li.dropdown .dropdown-menu li a {
  color: white;
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 500;
  display: block;
  padding: 0.5rem 0;
}

.nav ul li.dropdown .dropdown-menu li a:hover {
  color: #FD8700;
}

.nav ul li.dropdown:hover .dropdown-menu {
  display: block;
}

.color1 {
  fill: white;
}

.color2 {
  fill: #FD8700;
}

.hamburger {
  flex-direction: column;
  justify-content: space-around;
  width: 30px;
  height: 24px;
  z-index: 1001;
  background: none;
  border: none;
  cursor: pointer;
}

.hamburger {
  display: none;
}

.hamburger span {
  display: block;
  width: 100%;
  height: 3px;
  background: white;
  border-radius: 3px;
}

.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border: 0;
}

@media (max-width: 768px) {
  .hamburger {
    display: flex;
  }

  .nav {
    position: fixed;
    top: 0;
    right: -300px;
    width: 250px;
    height: 100%;
    background-color: #333333;
    color: white;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    transition: right 0.7s ease-in-out;
    z-index: 999;
    display: flex;
    flex-direction: column;
    padding: 6rem 1rem;
  }

  .nav.show {
    right: 0;
  }

  .nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-end;
    padding-right: 20px;
  }

  .nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: 500;
    transition: color 0.5s;
    padding-right: 10px;
    text-align: right;
  }

  .nav ul li a:hover {
    color: #fd8700;
  }

  .nav ul li.highlight a {
    color: #FD8700;
    font-weight: 500;
    border: 2px solid #FD8700;
    padding: 5px 5px;
    text-align: center;
    margin-right: 0px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
  }

  .nav ul li.highlight a:hover {
    background-color: #FD8700;
    color: white;
    transition: 0.5s;
  }
}

@media (min-width: 849px) and (max-width: 1200px) {
  .hamburger {
    display: flex;
  }

  .nav {
    position: fixed;
    top: 0;
    right: -300px;
    width: 250px;
    height: 100%;
    background-color: #333333;
    color: white;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
    transition: right 0.7s ease-in-out;
    z-index: 999;
    display: flex;
    flex-direction: column;
    padding: 6rem 1rem;
  }

  .nav.show {
    right: 0;
  }

  .nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    align-items: flex-end;
    padding-right: 20px;
  }

  .nav ul li a {
    color: white;
    text-decoration: none;
    font-size: 1.2rem;
    font-weight: 500;
    transition: color 0.5s;
    padding-right: 10px;
    text-align: right;
  }

  .nav ul li a:hover {
    color: #fd8700;
  }

  .nav ul li.highlight a {
    color: #FD8700;
    font-weight: 500;
    border: 2px solid #FD8700;
    padding: 5px 5px;
    text-align: center;
    margin-right: 0px;
    border-radius: 5px;
    display: inline-block;
    white-space: nowrap;
  }

  .nav ul li.highlight a:hover {
    background-color: #FD8700;
    color: white;
    transition: 0.5s;
  }
}
</style>