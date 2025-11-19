// Variável para controlar a posição do slide atual
let slideIndex = 1;
// Função para mostrar o slide atual
showSlides(slideIndex);

// Função para avançar o slide automaticamente a cada 2.5 segundos (para alterar o segundos e alterar o 2500, para 4000 (4 segundos) por exemplo)
let slideInterval = setInterval(() => {
  plusSlides(1);
}, 2500);

// Parar o avanço automático quando o cursor do mouse estiver sobre o slide
const slides = document.querySelectorAll('img');

// Itera sobre cada slide e adiciona o ouvinte de eventos
slides.forEach(slide => {
  slide.addEventListener('mouseenter', () => {
    clearInterval(slideInterval);
  });
});

// Continuar o avanço automático quando o cursor do mouse sair do slide
slides.forEach(slide => {
  slide.addEventListener('mouseleave', () => {
    slideInterval = setInterval(() => {
      plusSlides(1);
    }, 2500);
  });
});

// Função para avançar ou retroceder o slide
function plusSlides(n) {
  showSlides(slideIndex += n);
}

// Função para mostrar um slide específico
function currentSlide(n) {
  showSlides(slideIndex = n);
}

function showSlides(n) {
  let i;
  let slides = document.getElementsByClassName("slide");
  let dots = document.getElementsByClassName("dot");
  // Se a posição atual for maior que o número de slides, volte para o primeiro slide
  if (n > slides.length) {slideIndex = 1}    
  // Se a posição atual for menor que 1, vá para o último slide
  if (n < 1) {slideIndex = slides.length}
  // Ocultar todos os slides
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";  
  }
  // Remover a classe "active" de todos os pontos
  for (i = 0; i < dots.length; i++) {
      dots[i].className = dots[i].className.replace(" active", "");
  }
  // Exibir o slide atual e marcar o ponto correspondente como ativo
  slides[slideIndex-1].style.display = "block";  
  dots[slideIndex-1].className += " active";
}
