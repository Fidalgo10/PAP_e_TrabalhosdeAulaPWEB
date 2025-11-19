<?php
include 'DBConnection.php';
session_start();

if (!isset($_SESSION["iduser"])) {
    header("Location: index.php");
    exit;
}

$perfil = $_SESSION["perfil"];
$iduser = $_SESSION["iduser"];

// Consultas por perfil
if ($perfil == 'admin') {
    $resUtilizadores = mysqli_query($link, "SELECT COUNT(*) AS total FROM utilizadores");
    $totalUtilizadores = mysqli_fetch_assoc($resUtilizadores)['total'];

    $resAtletas = mysqli_query($link, "SELECT COUNT(*) AS total FROM atletas");
    $totalAtletas = mysqli_fetch_assoc($resAtletas)['total'];

    $resTreinadores = mysqli_query($link, "SELECT COUNT(*) AS total FROM treinadores");
    $totalTreinadores = mysqli_fetch_assoc($resTreinadores)['total'];

    $resModalidades = mysqli_query($link, "SELECT COUNT(*) AS total FROM modalidade");
    $totalModalidades = mysqli_fetch_assoc($resModalidades)['total'];

    $resLogs = mysqli_query($link, "SELECT COUNT(*) AS total FROM logs");
    $totalLogs = mysqli_fetch_assoc($resLogs)['total'];
}

if ($perfil == 'treinador') {
    $resAtletas = mysqli_query($link, "SELECT COUNT(*) AS total FROM atletas WHERE id_treinador = (
        SELECT id_treinador FROM treinadores WHERE id_utilizador = $iduser
    )");
    $totalAtletas = mysqli_fetch_assoc($resAtletas)['total'];
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Controlo</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Painel de Controlo">
    <meta name="author" content="okler.net">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="css/theme.css" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="css/skins/default.css" />

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/custom.css">

    <!-- Head Libs -->
    <script src="vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body">
    <?php include 'header.php'; ?>

    <div class="inner-wrapper">
        <?php
        if ($perfil == 'admin') {
            include 'admin/menuadmin.php';
        } elseif ($perfil == 'treinador') {
            include 'treinadores/menutreinador.php';
        } else {
            include 'atletas/menuatleta.php';
        }
        ?>

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Painel de Controlo</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Página inicial / </span></li>
                    </ol>
                </div>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Bem-vindo, <?= $_SESSION["nome"] ?>!</h2>
                        </header>
                        <div class="card-body">
                            <?php
                            if ($perfil == 'admin') {
                                echo "<p>Utilize o menu lateral para gerir utilizadores, atletas, treinadores, modalidades e logs.</p>";
                            } elseif ($perfil == 'treinador') {
                                echo "<p>Consulte os seus atletas, associe e crie novos atletas e os seus dados pessoais.</p>";
                            } else {
                                echo "<p>Consulte o seu perfil e veja qual é a sua modalidade atribuida dentro das que existem assim como os treinadores.</p>";
                            }
                            ?>
                        </div>
                    </section>
                </div>
            </div>

            <div class="row">
                <?php if ($perfil == 'admin'): ?>
                    <div class="col-md-6 col-xl-4">
                        <section class="card card-featured-left card-featured-primary mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-primary"><i class="fa fa-users"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Utilizadores</h4>
                                            <strong class="amount"><?= $totalUtilizadores ?></strong>
                                        </div>
                                        <div class="summary-footer">
                                            <a href="utilizadores/listar.php" class="text-muted text-uppercase">(ver lista)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <section class="card card-featured-left card-featured-success mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-success"><i class="fa fa-child"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Atletas associados</h4>
                                            <strong class="amount"><?= $totalAtletas ?></strong>
                                        </div>
                                        <div class="summary-footer">
                                            <a href="atletas/listar.php" class="text-muted text-uppercase">(ver lista)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <section class="card card-featured-left card-featured-warning mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-warning"><i class="fa fa-user"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Treinadores associados</h4>
                                            <strong class="amount"><?= $totalTreinadores ?></strong>
                                        </div>
                                        <div class="summary-footer">
                                            <a href="treinadores/listar.php" class="text-muted text-uppercase">(ver lista)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <section class="card card-featured-left card-featured-info mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-info"><i class="fa fa-futbol-o"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Modalidades</h4>
                                            <strong class="amount"><?= $totalModalidades ?></strong>
                                        </div>
                                        <div class="summary-footer">
                                            <a href="modalidades/listar.php" class="text-muted text-uppercase">(ver lista)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="col-md-6 col-xl-4">
                        <section class="card card-featured-left card-featured-dark mb-3">
                            <div class="card-body">
                                <div class="widget-summary">
                                    <div class="widget-summary-col widget-summary-col-icon">
                                        <div class="summary-icon bg-dark"><i class="fa fa-file-text-o"></i></div>
                                    </div>
                                    <div class="widget-summary-col">
                                        <div class="summary">
                                            <h4 class="title">Logs</h4>
                                            <strong class="amount"><?= $totalLogs ?></strong>
                                        </div>
                                        <div class="summary-footer">
                                            <a href="admin/logs.php" class="text-muted text-uppercase">(ver logs)</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</section>

<!-- Vendor JS -->
<script src="vendor/jquery/jquery.js"></script>
<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.js"></script>
<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="vendor/common/common.js"></script>
<script src="vendor/nanoscroller/nanoscroller.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="vendor/jquery-placeholder/jquery-placeholder.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="js/theme.js"></script>

<!-- Theme Custom -->
<script src="js/custom.js"></script>

<!-- Theme Initialization -->
<script src="js/theme.init.js"></script>
</body>
</html>

