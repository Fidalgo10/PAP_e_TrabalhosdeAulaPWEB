//Este JS faz com que em todas as pagina (menos no index) o cabeçalho encolha ao rolar para baixo e o btn apareça no canto superior esquerdo, e ao rolar para cima o cabeçalho aparece e o btn volta para o cabeçalho

document.addEventListener('DOMContentLoaded', function () {
    const voltarInicioBtn = document.getElementById('voltar-inicio');
    const headerElement = document.querySelector('.cabecalho');
    let lastScrollTop = 0;
  
    window.addEventListener('scroll', function () {
      let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
      
      if (scrollTop > lastScrollTop) {
        // Scrolling down
        headerElement.style.top = '-100px'; // Esconder o cabeçalho
        voltarInicioBtn.classList.add('fixed-top-left');
        voltarInicioBtn.style.backgroundColor = 'white'; // Cor de fundo quando fixo
        voltarInicioBtn.style.color = 'darkblue'; // Cor do texto quando fixo
      } else {
        // Scrolling up
        voltarInicioBtn.classList.remove('fixed-top-left');
        headerElement.style.top = '0'; // Mostrar o cabeçalho
        voltarInicioBtn.style.backgroundColor = 'darkblue'; // Cor de fundo no cabeçalho
        voltarInicioBtn.style.color = 'white'; // Cor do texto no cabeçalho
      }
  
      lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Para rolagens negativas
    });
  
    voltarInicioBtn.addEventListener('click', function () {
      window.location.href = 'index.html';
    });
  });
  