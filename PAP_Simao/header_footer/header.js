// Espera até que todo o conteúdo HTML esteja carregado
document.addEventListener('DOMContentLoaded', function() {
  // Obtém referências para os elementos necessários
  const menuButton = document.getElementById("menu-button");
  const pageContent = document.getElementById("page-content");
  const linkList = document.getElementById("link-list");

  // Verifica se o botão do menu está presente no DOM
  if (menuButton) {
      let isPageHidden = false;

      // Adiciona um ouvinte de eventos para o botão do menu
      menuButton.addEventListener("click", function() {
          if (!isPageHidden && pageContent && linkList) {
              // Altera o fundo do corpo para azul e oculta o conteúdo da página
              document.body.style.backgroundColor = "blue";
              pageContent.style.display = "none";
              linkList.style.display = "block";
              isPageHidden = true;
          } else {
              // Volta ao estilo original do corpo e exibe o conteúdo da página normalmente
              document.body.style.backgroundColor = "";
              if (pageContent) {
                  pageContent.style.display = "block";
              }
              if (linkList) {
                  linkList.style.display = "none";
              }
              isPageHidden = false;
          }
      });

      // Adiciona um ouvinte de eventos para redimensionamento da janela
      window.addEventListener("resize", function() {
          if (isPageHidden && pageContent && linkList) {
              // Volta ao estilo original do corpo e exibe o conteúdo da página normalmente
              document.body.style.backgroundColor = "";
              pageContent.style.display = "block";
              linkList.style.display = "none";
              isPageHidden = false;
          }
      });
  } else {
      console.error("Elemento menu-button não encontrado no DOM.");
  }

  // Verifica se o submenu de equipas está presente no DOM antes de adicionar os event listeners
  const equipasToggle = document.querySelector('.equipas-toggle');
  const equipasSubmenu = document.querySelector('.equipas-submenu_telapequena');

  if (equipasToggle && equipasSubmenu) {
      // Adiciona um ouvinte de eventos para mostrar/ocultar o submenu de equipas
      equipasToggle.addEventListener('click', function(event) {
          event.stopPropagation();

          // Fecha o submenu de competições, se estiver aberto
          const compSubmenu = document.querySelector('.comp-submenu_telapequena');
          if (compSubmenu.classList.contains('show')) {
              compSubmenu.classList.remove('show');
          }

          // Alterna a visibilidade do submenu de equipas
          equipasSubmenu.classList.toggle('show');
      });

      // Fecha o submenu de equipas se clicar fora dele
      document.addEventListener('click', function(event) {
          const isClickedOutsideEquipas = !equipasToggle.contains(event.target) && !equipasSubmenu.contains(event.target);

          if (isClickedOutsideEquipas) {
              equipasSubmenu.classList.remove('show');
          }
      });
  } else {
      console.error("Elementos equipasToggle ou equipasSubmenu não encontrados no DOM.");
  }

  // Verifica se o submenu de competições está presente no DOM antes de adicionar os event listeners
  const compToggle = document.querySelector('.comp-toggle');
  const compSubmenu = document.querySelector('.comp-submenu_telapequena');

  if (compToggle && compSubmenu) {
      // Adiciona um ouvinte de eventos para mostrar/ocultar o submenu de competições
      compToggle.addEventListener('click', function(event) {
          event.stopPropagation();

          // Fecha o submenu de equipas, se estiver aberto
          const equipasSubmenu = document.querySelector('.equipas-submenu_telapequena');
          if (equipasSubmenu.classList.contains('show')) {
              equipasSubmenu.classList.remove('show');
          }

          // Alterna a visibilidade do submenu de competições
          compSubmenu.classList.toggle('show');
      });

      // Fecha o submenu de competições se clicar fora dele
      document.addEventListener('click', function(event) {
          const isClickedOutsideComp = !compToggle.contains(event.target) && !compSubmenu.contains(event.target);

          if (isClickedOutsideComp) {
              compSubmenu.classList.remove('show');
          }
      });
  } else {
      console.error("Elementos compToggle ou compSubmenu não encontrados no DOM.");
  }

  // Variáveis para rastrear o estado de rolagem
  let lastScrollTop = 0;
  const headerElement = document.querySelector('.cabecalho');

  // Verifica se o elemento headerElement está presente no DOM antes de adicionar o event listener
  if (headerElement) {
      // Adiciona um ouvinte de eventos para rolagem da janela
      window.addEventListener('scroll', function() {
          let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

          if (scrollTop > lastScrollTop) {
              // Rolagem para baixo
              headerElement.style.top = '-100px'; // Ajusta a posição para ocultar o cabeçalho
          } else {
              // Rolagem para cima
              headerElement.style.top = '0';
          }
          
          lastScrollTop = scrollTop <= 0 ? 0 : scrollTop; // Garante que não há valores negativos
      }, false);
  } else {
      console.error("Elemento headerElement não encontrado no DOM.");
  }
});

// JS para abrir o submenu em "Equipas"
document.addEventListener('DOMContentLoaded', function() {
  const submenuEquipas = document.querySelector('.submenu');
  const menuEquipas = document.querySelector('.cabecalho-menu-item.equipas');

  let isSubmenuOpen = false;

  // Mostra ou esconde o submenu ao clicar em "Equipas"
  menuEquipas.addEventListener('click', function(event) {
    event.preventDefault();

    // Alterna a visibilidade do submenu
    isSubmenuOpen = !isSubmenuOpen;
    submenuEquipas.classList.toggle('show', isSubmenuOpen);
  });

  // Fecha o submenu se clicar fora dele
  document.addEventListener('click', function(event) {
    const target = event.target;

    // Verifica se o clique foi dentro do submenu ou no botão "Equipas"
    const isClickInsideSubmenu = submenuEquipas.contains(target);
    const isClickInsideMenu = menuEquipas.contains(target);

    if (!isClickInsideSubmenu && !isClickInsideMenu && isSubmenuOpen) {
      submenuEquipas.classList.remove('show');
      isSubmenuOpen = false;
    }
  });

  // Evitar que o submenu feche quando se clica dentro dele
  submenuEquipas.addEventListener('click', function(event) {
    event.stopPropagation();
  });

  // Fechar o submenu ao rolar a página para baixo
  window.addEventListener('scroll', function() {
    if (isSubmenuOpen) {
      submenuEquipas.classList.remove('show');
      isSubmenuOpen = false;
    }
  });
});

// JS para o submenu dos campeonatos
document.addEventListener("DOMContentLoaded", function() {
  const campeonatosItems = document.querySelectorAll(".campeonatos");
  const submenuCampeonatos = document.querySelectorAll(".submenu-campeonatos");
  const chevrons = document.querySelectorAll(".campeonatos .fas");

  // Adiciona um ouvinte de eventos para cada item de campeonato
  campeonatosItems.forEach((item, index) => {
    item.addEventListener("click", () => {
      const submenu = submenuCampeonatos[index];
      const chevron = chevrons[index];
      if (submenu.classList.contains("show")) {
        // Fecha o submenu se já estiver aberto
        submenu.classList.remove("show");
        setTimeout(() => submenu.style.display = "none", 300); // Tempo para coincidir com a duração da transição
        chevron.classList.remove("chevron-up");
      } else {
        // Abre o submenu se estiver fechado
        submenu.style.display = "block";
        setTimeout(() => submenu.classList.add("show"), 10); // Permite que o navegador registre a mudança de exibição
        chevron.classList.add("chevron-up");
      }
    });
  });

  // Fecha o submenu se clicar fora dele
  submenuCampeonatos.forEach((submenu, index) => {
    submenu.addEventListener("click", () => {
      submenu.classList.remove("show");
      setTimeout(() => submenu.style.display = "none", 300); // Tempo para coincidir com a duração da transição
      chevrons[index].classList.remove("chevron-up");
    });
  });

  // Fecha o submenu se clicar fora dele
  document.addEventListener("click", (event) => {
    campeonatosItems.forEach((item, index) => {
      const submenu = submenuCampeonatos[index];
      const chevron = chevrons[index];
      if (!item.contains(event.target) && !submenu.contains(event.target)) {
        submenu.classList.remove("show");
        setTimeout(() => submenu.style.display = "none", 300); // Tempo para coincidir com a duração da transição
        chevron.classList.remove("chevron-up");
      }
    });
  });
});
