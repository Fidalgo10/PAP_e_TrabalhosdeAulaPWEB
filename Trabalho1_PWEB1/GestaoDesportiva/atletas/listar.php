<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"]) || ($_SESSION["perfil"] != 'admin' && $_SESSION["perfil"] != 'treinador')) {
    header("Location: ../index.php");
    exit;
}
$perfil = $_SESSION["perfil"];

?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Atletas</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Painel de Controlo">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="../vendor/animate/animate.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="../vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="../vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="../css/theme.css" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="../css/skins/default.css" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="../css/custom.css">

    <!-- Head Libs -->
    <script src="../vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body">
<?php include '../header.php'; ?>
<div class="inner-wrapper">
        <?php
        if ($perfil == 'admin') {
            include '../admin/menuadmin.php';
        } elseif ($perfil == 'treinador') {
            include '../treinadores/menutreinador.php';
        }
        ?>

<section role="main" class="content-body">
            <header class="page-header">
                <h2>Listar Atletas</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Página inicial / Atletas / Listar Atletas Associados</span></li>
                    </ol>
                </div>
            </header>
    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Modalidade</th>
                                <th>Treinador</th>
                                <th>Idade</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($perfil == 'treinador') {
                                    $id_utilizador = $_SESSION["iduser"];

                                    // Obter o ID do treinador a partir do ID do utilizador logado
                                    $res_treinador = mysqli_query($link, "SELECT id_treinador FROM treinadores WHERE id_utilizador = $id_utilizador");
                                    $treinador = mysqli_fetch_assoc($res_treinador);
                                    $id_treinador = $treinador['id_treinador'];

                                    $res = mysqli_query($link, "
                                        SELECT a.id_atleta, u.nome AS nome_atleta, m.nome AS modalidade, tr.nome AS nome_treinador, a.idade
                                        FROM atletas a
                                        JOIN utilizadores u ON a.id_utilizador = u.id_utilizador
                                        JOIN modalidade m ON a.id_modalidade = m.id_modalidade
                                        JOIN treinadores t ON a.id_treinador = t.id_treinador
                                        JOIN utilizadores tr ON t.id_utilizador = tr.id_utilizador
                                        WHERE a.id_treinador = $id_treinador
                                    ");
                                } else {
                                    // Admin vê todos
                                    $res = mysqli_query($link, "
                                        SELECT a.id_atleta, u.nome AS nome_atleta, m.nome AS modalidade, tr.nome AS nome_treinador, a.idade
                                        FROM atletas a
                                        JOIN utilizadores u ON a.id_utilizador = u.id_utilizador
                                        JOIN modalidade m ON a.id_modalidade = m.id_modalidade
                                        JOIN treinadores t ON a.id_treinador = t.id_treinador
                                        JOIN utilizadores tr ON t.id_utilizador = tr.id_utilizador
                                    ");
                                }
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<tr>";
                                echo "<td>{$row['id_atleta']}</td>";
                                echo "<td>{$row['nome_atleta']}</td>";
                                echo "<td>{$row['modalidade']}</td>";
                                echo "<td>{$row['nome_treinador']}</td>";
                                echo "<td>{$row['idade']}</td>";
                                echo "<td><a class='btn btn-sm btn-primary' href='editar.php?id_atleta={$row['id_atleta']}'>Editar <i class='fa fa-pencil'></i></a></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
</section>
</div>
</section>

<!-- Vendor JS -->
<script src="../vendor/jquery/jquery.js"></script>
<script src="../vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="../vendor/popper/umd/popper.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.js"></script>
<script src="../vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="../vendor/common/common.js"></script>
<script src="../vendor/nanoscroller/nanoscroller.js"></script>
<script src="../vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="../vendor/jquery-placeholder/jquery-placeholder.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="../js/theme.js"></script>

<!-- Theme Custom -->
<script src="../js/custom.js"></script>

<!-- Theme Initialization Files -->
<script src="../js/theme.init.js"></script>
</body>
</html>