<?php
$pageTitle = "Séniores"; // Define o título da página
include './header_footer/header.php'; // Inclui o cabeçalho da página
?>
<link rel="stylesheet" href="css/style_equipas.css"> <!-- Link para o arquivo CSS de estilo -->
<div id="conteudo">
    <h1 class="conteudo-titulo">Plantel dos séniores</h1> <!-- Título principal do conteúdo -->
    <div style="display: flex; justify-content: center; flex-wrap: wrap;">
        <div style="display: flex; justify-content: center;">
            <!-- Imagem da equipa dos séniores -->
            <img id="equipa_seniores" src="img/seniores/equipatecnicaseniores.jpg" alt="Plantel dos Séniores" title="Plantel dos Séniores" class="img-fluid">
        </div>
        <div>
            <!-- Subtítulo com a descrição do plantel -->
            <h6 class="conteudo-subtitulo">
                O plantel da equipa dos Séniores conta neste momento com 25 integrantes,
                dos quais 21 são jogadores e o restante faz parte da equipa técnica.
            </h6>
        </div>
    </div>
</div>

<br>
<!-- Título da secção dos jogadores -->
<div class="titulo_jogadores">
    <hr class="hr_jogadores">
    <div class="word">Jogadores</div>
    <hr class="hr_jogadores">
</div>

<br>
<!-- Container para os jogadores -->
<div class="container">
    <div class="row">
        <?php
        // Array com as informações dos jogadores
        $jogadores = [
            ["img/seniores/HORTELÃO 4.jpg", "Miguel Hortelão", "Hortelão #4"],
            ["img/seniores/sérginho 5.jpg", "Sérgio Saraiva", "Serginho #5"],
            ["img/seniores/CLÁUDIO 6.jpg", "Cláudio", "Cláudio #6"],
            ["img/seniores/SNIPER 7.jpg", "Guilherme Monteiro", "Sniper #7"],
            ["img/seniores/BAPTISTA 8.jpg", "Pedro Baptista", "Baptista #8"],
            ["img/seniores/copas 9.jpg", "Cláudio Baptista", "Copas #9"],
            ["img/seniores/JOÃO PEDRO 10.jpg", "João Pedro", "João Pedro #10"],
            ["img/seniores/DIAMANTINO 11.jpg", "João Pedro Diamantino", "Diamantino #11"],
            ["img/seniores/NUNO ANTUNES 13.jpg", "Nuno Antunes", "Antunes #13"],
            ["img/seniores/Zé carlos 14.jpg", "Zé Carlos", "Zé Carlos #14"],
            ["img/seniores/HURTADO 15.jpg", "Hurtado", "Hurtado #15"],
            ["img/seniores/Pedro Sousa 16.jpg", "Pedro Sousa", "Sousa #16"],
            ["img/seniores/DALTON 18.jpg", "Agostinho Chipesse", "Dalton #18"],
            ["img/seniores/LUCAS 19.jpg", "Lucas", "Lucas #19"],
            ["img/seniores/RÚBEN 20.jpg", "Rúben Gomes", "Rúben #20"],
            ["img/seniores/mateus 21.jpg", "Mateus Pais", "Mateus #21"],
            ["img/seniores/miguel gomes 23.jpg", "Miguel Gomes", "Miguel Gomes #23"],
            ["img/seniores/ÓSCAR 31.jpg", "Óscar", "Óscar #31"],
            ["img/seniores/JOÃO CARLOS 66.jpg", "João Carlos", "João Carlos #66"],
            ["img/seniores/MORAIS 90.jpg", "Ándre Morais", "Ándre Morais #90"],
            ["img/seniores/MIGUEL DIAS 99.jpg", "Miguel Dias", "Miguel Dias #99"]
        ];

        // Loop para exibir cada jogador
        foreach ($jogadores as $jogador) {
            echo '<div class="item">'; // Início do item do jogador
            echo '<button><img src="' . $jogador[0] . '" alt="' . $jogador[1] . '" title="' . $jogador[1] . '">'; // Imagem do jogador
            echo '<p><b>' . $jogador[2] . '</b></p>'; // Nome e número do jogador
            echo '</button>';
            echo '</div>'; // Fim do item do jogador
        }
        ?>
    </div>
</div>

<br>
<!-- Título da secção da equipa técnica -->
<div class="titulo_equipatecnica">
    <hr class="hr_equipatecnica">
    <div class="word_equipatecnica"><p>Equipa Técnica</p></div>
    <hr class="hr_equipatecnica">
</div>

<br><br>
<!-- Container para a equipa técnica -->
<div class="container">
    <div class="row">
        <?php
        // Array com as informações da equipa técnica
        $equipaTecnica = [
            ["img/nunonascimento.jpg", "Nuno Nascimento", "Treinador Principal"],
            ["img/seniores/rui.jpg", "Rui Almeida", "Treinador Adjunto"],
            ["img/seniores/serambeque.jpg", "Joaquim Serambeque", "Treinador Adjunto (GR)"],
            ["img/benjamins/PedroFidalgo_DiretorDesportivo.jpg", "Pedro Fidalgo", "Diretor Desportivo"]
        ];

        // Loop para exibir cada membro da equipa técnica
        foreach ($equipaTecnica as $membro) {
            echo '<div class="item_equipatecnica">'; // Início do item da equipa técnica
            echo '<button><img src="' . $membro[0] . '" alt="' . $membro[1] . '" title="' . $membro[1] . '">'; // Imagem do membro da equipa técnica
            echo '<p><b>' . $membro[1] . '<br>' . $membro[2] . '</b></p>'; // Nome e função do membro da equipa técnica
            echo '</button>';
            echo '</div>'; // Fim do item da equipa técnica
        }
        ?>
    </div>
</div>

<?php
include './header_footer/footer.php'; // Inclui o rodapé da página
?>
