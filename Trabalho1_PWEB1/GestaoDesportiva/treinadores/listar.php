<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"]) || ($_SESSION["perfil"] != 'admin' && $_SESSION["perfil"] != 'atleta')) {
    header("Location: ../index.php");
    exit;
}

$perfil = $_SESSION["perfil"];
$iduser = $_SESSION["iduser"];
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Treinadores</title>
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
        } elseif ($perfil == 'atleta') {
            include '../atletas/menuatleta.php';
        }
        ?>

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Listar Treinadores Associados</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Treinadores / Listar</span></li>
                    </ol>
                </div>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <section class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Modalidade</th>
                                        <?php if ($perfil == 'admin') echo "<th>Ações</th>"; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Se for atleta, descobrir o id_treinador associado
                                    $id_treinador_atleta = null;
                                    if ($perfil == 'atleta') {
                                        $q = mysqli_query($link, "SELECT id_treinador FROM atletas WHERE id_utilizador = $iduser LIMIT 1");
                                        if ($q && mysqli_num_rows($q) > 0) {
                                            $row = mysqli_fetch_assoc($q);
                                            $id_treinador_atleta = $row['id_treinador'];
                                        }
                                    }

                                    $res = mysqli_query($link, "
                                        SELECT t.id_treinador, u.nome AS nome_utilizador, m.nome AS modalidade
                                        FROM treinadores t
                                        JOIN utilizadores u ON t.id_utilizador = u.id_utilizador
                                        JOIN modalidade m ON t.id_modalidade = m.id_modalidade
                                        ORDER BY t.id_treinador
                                    ");
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        $destacar = ($perfil == 'atleta' && $row['id_treinador'] == $id_treinador_atleta) ? "table-success" : "";

                                        echo "<tr class='$destacar'>";
                                        echo "<td>{$row['id_treinador']}</td>";
                                        echo "<td>{$row['nome_utilizador']}</td>";
                                        echo "<td>{$row['modalidade']}</td>";

                                        if ($perfil == 'admin') {
                                            echo "<td><a class='btn btn-sm btn-primary' href='editar.php?id_treinador={$row['id_treinador']}'>Editar <i class='fa fa-pencil'></i></a></td>";
                                        }

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
<script src="../js/custom.js"></script>
<script src="../js/theme.init.js"></script>
</body>
</html>
