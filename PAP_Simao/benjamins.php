<?php
$pageTitle = "Benjamins";
include './header_footer/header.php';
?>

<link rel="stylesheet" href="css/style_equipas.css">
<div id="conteudo" class="container-fluid border">
    <h1 class="conteudo-titulo">Plantel da equipa benjamim</h1>
    <div style="display: flex; justify-content: center; flex-wrap: wrap;">
        <div style="display: flex; justify-content: center;">
            <img id="equipa_benjamins" src="img/benjamins/equipa_benjamins.jpg" alt="Plantel dos Benjamins" title="Plantel dos Benjamins" class="img-fluid">
        </div>
        <div>
            <h6 class="conteudo-subtitulo">O plantel da equipa dos benjamins conta com 23 integrantes,
                dos quais 20 são jogadores e o restante faz parte da equipa técnica.
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
        $jogadores_benjamins = [
            ["img/benjamins/Duarte_3.jpg", "Duarte", "Duarte #3"],
            ["img/benjamins/Leandro_5.jpg", "Leandro", "Leandro #5"],
            ["img/benjamins/MartimCunha_6.jpg", "Martim Cunha", "Martim Cunha #6"],
            ["img/benjamins/Diego_7.jpg", "Diego", "Diego #7"],
            ["img/benjamins/DiegoDuarte_8.jpg", "Diego Duarte", "Diego Duarte #8"],
            ["img/benjamins/Manuel_10.jpg", "Manuel", "Manuel #10"],
            ["img/benjamins/Alícia_11.jpg", "Alícia", "Alícia #11"],
            ["img/benjamins/Martim_12.jpg", "Martim", "Martim #12"],
            ["img/benjamins/Afonso_13.jpg", "Afonso", "Afonso #13"],
            ["img/benjamins/Núria_14.jpg", "Núria", "Núria #14"],
            ["img/benjamins/Pedro_16.jpg", "Pedro", "Pedro #16"],
            ["img/benjamins/Figo_17.jpg", "Figo", "Figo #17"],
            ["img/benjamins/Santiago_18.jpg", "Santiago", "Santiago #18"],
            ["img/benjamins/MiguelFidalgo_19.jpg", "Miguel Fidalgo", "Miguel Fidalgo #19"],
            ["img/benjamins/MartimFigueiredo_20.jpg", "Martim Figueiredo", "Martim Figueiredo #20"],
            ["img/benjamins/Santiago_21.jpg", "Santiago", "Santiago #21"],
            ["img/benjamins/Catarina_22.jpg", "Catarina", "Catarina #22"],
            ["img/benjamins/Tomás_25.jpg", "Tomás", "Tomás #25"],
            ["img/benjamins/JoãoMartim_27.jpg", "João Martim", "João Martim #27"],
            ["img/benjamins/Duarte_29.jpg", "Duarte", "Duarte #29"]
        ];

        // Loop para exibir cada jogador
        foreach ($jogadores_benjamins as $jogador) {
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

<br><br>
<div class="container">
    <div class="row">
        <?php
        // Array com as informações da equipa técnica
        $equipaTecnica_benjamins = [
            ["img/benjamins/JoãoPedro_TreinadorPrincipal.jpg", "João Pedro", "Treinador Principal"],
            ["img/benjamins/CláudioAbrantes_TreinadorAdjunto.jpg", "Cláudio Abrantes", "Treinador Adjunto"],
            ["img/benjamins/PedroFidalgo_DiretorDesportivo.jpg", "Pedro Fidalgo", "Diretor Desportivo"]
        ];

        // Loop para exibir cada membro da equipa técnica
        foreach ($equipaTecnica_benjamins as $membro) {
            echo '<div class="item_equipatecnica">';
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
