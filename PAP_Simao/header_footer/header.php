<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="/pap_simao/header_footer/header.css">
    <link rel="icon" href="/pap_simao/img/CFV_icon.ico" type="image/x-icon">
    <script src="https://kit.fontawesome.com/1165876da6.js" crossorigin="anonymous"></script>
</head>
<body>
    <header class="cabecalho">
        <a href="/pap_simao/index.php" class="logo-container">
            <img id="cabecalho-imagem" src="/pap_simao/img/vilanovenses logo sem fundo.png" alt="Símbolo vilanovenses">
            <div class="club-info">
                <div class="club-name">CF</div>
                <div class="club-name">Os Vilanovenses</div>
                <div class="club-since">desde 1935</div>
            </div>
        </a>
        <nav class="cabecalho-menu">
            <a class="cabecalho-menu-item" href="/pap_simao/historia.php">História</a>
            <div class="cabecalho-menu-item equipas" style="cursor: pointer;">Equipas
                <div class="submenu">
                    <div class="submenu-item align-left">
                        <a>Equipa Sénior</a><br>
                        <ul class="lista">
                            <li><a href="/pap_simao/seniores.php">Plantel</a></li>
                            <li class="campeonatos">Competições<i class="fas fa-chevron-down"></i></li>
                        </ul>
                        <div class="submenu-campeonatos-seniores submenu-campeonatos">
                            <ul>
                                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24058&seasonId=103" target="_blank">Campeonato</a></li>
                                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24065&seasonId=103" target="_blank">Taça</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="submenu-item align-center">
                        <a>Equipa Júnior</a><br>
                        <ul class="lista">
                            <li><a href="/pap_simao/juniores.php">Plantel</a></li>
                            <li class="campeonatos" id="campeonatos-align">Competições<i class="fas fa-chevron-down"></i></li>
                        </ul>
                        <div class="submenu-campeonatos-juniores submenu-campeonatos">
                            <ul>
                                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24345&seasonId=103" target="_blank">Campeonato</a></li>
                                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24854&seasonId=103" target="_blank">Taça</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="submenu-item align-right">
                        <a>Equipa Benjamins</a><br>
                        <ul class="lista">
                            <li><a href="/pap_simao/benjamins.php">Plantel</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <a class="cabecalho-menu-item" href="/pap_simao/loja.php">Loja</a>
            <?php if (isset($_SESSION['nome'])) { ?>
                <i class="fas fa-user user_simbolo"></i><span class="cabecalho-menu-nome" ><?= $_SESSION['nome'] ?></span><p class="barra_header">|</p>
                <a class="cabecalho-menu-login" href="/pap_simao/login/logout.php">Terminar sessão</a>
            <?php } else { ?>
                <i class="fas fa-user user_simbolo"></i><a class="cabecalho-menu-login" href="/pap_simao/login/signup.php">Sign up</a><p class="barra_header">|</p>
                <a class="cabecalho-menu-login" href="/pap_simao/login/login.php">Login</a>
            <?php } ?>
        </nav>
        <button id="menu-button" class="menu-button" aria-label="Menu">
            <i class="fa fa-bars"></i>
        </button>
    </header>
    <div id="link-list" style="display: none;">
        <?php if (isset($_SESSION['nome'])) { ?>
            <p style="color:white; font-weight: bold;"><i class="fas fa-user" style="margin-right: 6px;"></i><span><?= $_SESSION['nome'] ?></span> | <a href="/pap_simao/login/logout.php">Terminar sessão</a></p><br>
        <?php } else { ?>
            <p style="color: white;"><i class="fas fa-user" style="margin-right: 6px;"></i><a href="/pap_simao/login/signup.php">Sign up</a> | <a href="/pap_simao/login/login.php">Login</a></p><br>
        <?php } ?>
        <p><a href="/pap_simao/historia.php">História</a></p><br>
        <p><a href="/pap_simao/loja.php">Loja</a></p>
        <p class="equipas-toggle">Plantel das equipas</p>
        <div class="equipas-submenu_telapequena">
            <ul>
                <li><a href="/pap_simao/seniores.php">Equipa Sénior</a></li>             
                <li><a href="/pap_simao/juniores.php">Equipa Júnior</a></li>
                <li><a href="/pap_simao/benjamins.php">Equipa Benjamins</a></li>
            </ul>
        </div>
        <p class="comp-toggle">Competições</p>
        <div class="comp-submenu_telapequena">
            <ul>
                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24058&seasonId=103">LIGA CIMA/TAVFER (Séniores)</a></li>
                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24065&seasonId=103">TAÇA DE HONRA ASSOCIAÇÃO FUTEBOL DA GUARDA (Séniores)</a></li>
                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24345&seasonId=103">CAMPEONATO DISTRITAL SUB-19 (Júniores)</a></li>
                <li><a href="https://resultados.fpf.pt/Competition/Details?competitionId=24854&seasonId=103">TAÇA SUB-19 (Júniores)</a></li>
            </ul>
        </div>
    </div>
    <script src="/pap_simao/header_footer/header.js"></script>
    <div id="page-content">
