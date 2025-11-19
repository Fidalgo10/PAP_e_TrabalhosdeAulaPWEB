<?php
$pageTitle = "Júniores";
include './header_footer/header.php';
?>

<link rel="stylesheet" href="css/style_equipas.css">
<div id="conteudo" class="container-fluid border">
    <h1 class="conteudo-titulo">Plantel da equipa júnior</h1>
    <div style="display: flex; justify-content: center; flex-wrap: wrap;">
        <div style="display: flex; justify-content: center;">
            <img id="equipa_juniores" src="img/juniores/equipajuniores.jpg" alt="Plantel dos Juniores" title="Plantel dos Juniores" class="img-fluid">
        </div>
        <div>
            <h6 class="conteudo-subtitulo">O plantel da equipa dos Juniores conta com 29 integrantes,
                dos quais 24 são jogadores e o restante faz parte da equipa técnica.
            </h6>
        </div>
    </div>

</div>

<br><br><br><br>
<div class="titulo_jogadores">
    <hr class="hr_jogadores">
    <div class="word">Jogadores</div>
    <hr class="hr_jogadores">
</div>


<br><br>
<div class="container">
    <div class="row">
        <?php
        // Array com as informações dos jogadores
        $jogadores_juniores = [
            ["img/juniores/DUARTE 1.jpg", "Duarte Simões", "Duarte #1"],
            ["img/juniores/BERNARDO 3.jpg", "Bernardo Sá", "Bernardo #3"],
            ["img/juniores/RUI 4.jpg", "Rui Almeida", "Rui #4"],
            ["img/juniores/FIDALGO 5.jpg", "Simão Fidalgo", "Fidalgo #5"],
            ["img/juniores/FARIAS 6.jpg", "Bernardo Farias", "Farias #6"],
            ["img/juniores/MARCELO 7.jpg", "Marcelo", "Marcelo #7"],
            ["img/juniores/TREPADO 8.jpg", "Tomás Trepado", "Trepado #8"],
            ["img/juniores/LUCAS 9.jpg", "Lucas", "Lucas #9"],
            ["img/juniores/TANAKA 10.jpg", "Gonçalo Reis", "Tanaka #10"],
            ["img/juniores/QUÉVIN 11.jpg", "Quévin", "Quévin #11"],
            ["img/juniores/ALEX 14.jpg", "Alex", "Alex #14"],
            ["img/juniores/MARCO 15.jpg", "Marco", "Marco #15"],
            ["img/juniores/EDIN 16.jpg", "Édin", "Édin #16"],
            ["img/juniores/RODRIGO 17.jpg", "Rodrigo Costa", "Costa #17"],
            ["img/juniores/LEITÃO 18.jpg", "Fernando Leitão", "Nando Leitão #18"],
            ["img/juniores/PINHANÇOS 19.jpg", "Rodrigo Pinhanços", "Pinhanços #19"],
            ["img/juniores/SANDRO 20.jpg", "Sandro", "Sandro #20"],
            ["img/juniores/DINIS 26.jpg", "Dinis Fidalgo", "Dinis #26"],
            ["img/juniores/JOEL 29.jpg", "Joel", "Joel #29"],
            ["img/juniores/SIMÃO SANTOS 30.jpg", "Simão Santos", "Simão #30"],
            ["img/juniores/Caramelo 33.jpg", "Cristiano Caramelo", "Cris #33"],
            ["img/juniores/EDGAR 44.jpg", "Edgar", "Edgar #44"],
            ["img/juniores/VASCO 77.jpg", "Vasco", "Vasco #77"],
            ["img/juniores/JOTA 99.jpg", "João Pedro", "Jota #99"]
        ];

        // Loop para exibir cada jogador
        foreach ($jogadores_juniores as $jogador) {
            echo '<div class="item">';
            echo '<button><img src="' . $jogador[0] . '" alt="' . $jogador[1] . '" title="' . $jogador[1] . '">';
            echo '<p><b>' . $jogador[2] . '</b></p>';
            echo '</button>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<br>
<div class="titulo_equipatecnica">
    <hr class="hr_equipatecnica">
    <div class="word_equipatecnica"><p>Equipa Técnica</p></div>
    <hr class="hr_equipatecnica">
</div>

<br>
<div class="container">
    <div class="row">
        <?php
        // Array com as informações da equipa técnica
        $equipaTecnica_juniores = [
            ["img/nunonascimento.jpg", "Nuno Nascimento", "Treinador Principal"],
            ["img/juniores/viriatoviana.jpg", "Viriato Viana", "Treinador Adjunto"],
            ["img/juniores/heldercruz.jpg", "Hélder Cruz", "Treinador Adjunto"],
            ["img/benjamins/PedroFidalgo_DiretorDesportivo.jpg", "Pedro Fidalgo", "Diretor Desportivo"],
            ["img/juniores/carolinaoliveira.jpg", "Carolina Oliveira", "Diretora de comunicação"]
        ];

        // Loop para exibir cada membro da equipa técnica
        foreach ($equipaTecnica_juniores as $membro) {
            echo '<div class="item_equipatecnica_jun">';
            echo '<button><img src="' . $membro[0] . '" alt="' . $membro[1] . '" title="' . $membro[1] . '">';
            echo '<p><b>' . $membro[1] . '<br>' . $membro[2] . '</b></p>';
            echo '</button>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<?php    
include './header_footer/footer.php'; // Inclui o rodapé
?>
