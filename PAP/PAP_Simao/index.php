<?php
$pageTitle = "Os Vilanovenses";
include './header_footer/header.php';
?>
<link rel="stylesheet" href="./css/css_index/style_index.css">
<link rel="stylesheet" href="./css/css_index/styleslide.css">
    <div id="conteudo"><br><br>
      <h1 class="conteudo-titulo"><mark style="background-color: darkblue; color: white"> CF Os Vilanovenses</mark></h1><br><br>
      <h2 class="conteudo-subtitulo">Clube de Futebol Os Vilanovenses</h2>
    </div>
    <div class="barra"></div><br>
    <br><br><br><br>
    <div class="slideshow-container">
      <div class="slide"><img src="img/slide/balneario.jpg" alt="Imagem 1"></div>
      <div class="slide"><img src="img/slide/equipa_sen.jpg" alt="Imagem 2"></div>
      <div class="slide"><img src="img/slide/equipa_sen2.jpg" alt="Imagem 3"></div>
      <div class="slide"><img src="img/slide/estadio.jpg" alt="Imagem 4"></div>
      <div class="slide"><img src="img/slide/estadio3.jpg" alt="Imagem 5"></div>
      <div class="slide"><img src="img/slide/placarCFvilanovenses.jpg" alt="Imagem 6"></div>
      <div class="slide"><img src="img/slide/Balneario_jun.jpg" alt="Imagem 7"></div>
      <div class="slide"><img src="img/slide/equipa_jun.jpg" alt="Imagem 8"></div>
      <div class="slide"><img src="img/slide/estadio_aeria.jpg" alt="Imagem 9"></div>
      <div class="slide"><img src="img/slide/ben1.jpg" alt="Imagem 10"></div>
      <div class="slide"><img src="img/slide/ben2.JPG" alt="Imagem 11"></div>
      <div class="slide"><img src="img/slide/ben3.JPG" alt="Imagem 12"></div>
      <div class="slide"><img src="img/slide/estadio_ban.jpg" alt="Imagem 13"></div>
    
      <div class="dot-container">
        <span class="dot" onclick="currentSlide(1)"></span> 
        <span class="dot" onclick="currentSlide(2)"></span> 
        <span class="dot" onclick="currentSlide(3)"></span> 
        <span class="dot" onclick="currentSlide(4)"></span> 
        <span class="dot" onclick="currentSlide(5)"></span> 
        <span class="dot" onclick="currentSlide(6)"></span> 
        <span class="dot" onclick="currentSlide(7)"></span> 
        <span class="dot" onclick="currentSlide(8)"></span> 
        <span class="dot" onclick="currentSlide(9)"></span> 
        <span class="dot" onclick="currentSlide(10)"></span> 
        <span class="dot" onclick="currentSlide(11)"></span> 
        <span class="dot" onclick="currentSlide(12)"></span> 
        <span class="dot" onclick="currentSlide(13)"></span> 
      </div>
    
      <div class="navigation-buttons">
        <a class="anterior" onclick="plusSlides(-1)">&#10094;</a>
        <a class="proximo" onclick="plusSlides(1)">&#10095;</a>
      </div>
    </div>
    <script src="js/index/script_slides.js"></script>

<?php    
    include './header_footer/footer.php'; // Inclui o rodapé
?>
