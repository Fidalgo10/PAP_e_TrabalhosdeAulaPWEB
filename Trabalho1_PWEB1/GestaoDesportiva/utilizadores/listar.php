<?php
session_start();
include "../DBConnection.php"; // Caminho RELATIVO correto

if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Utilizadores</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Painel de Controlo">
    <meta name="author" content="okler.net">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="../vendor/animate/animate.css">
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="../vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="../vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="../css/theme.css" />
    <link rel="stylesheet" href="../css/skins/default.css" />
    <link rel="stylesheet" href="../css/custom.css">
    <script src="../vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body">
<?php include '../header.php'; ?>
<div class="inner-wrapper">
<?php include '../admin/menuadmin.php'; ?>

<section role="main" class="content-body">
    <header class="page-header">
        <h2>Listar Utilizador</h2>
        <div class="right-wrapper text-right">
            <ol class="breadcrumbs">
                <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                <li><span>Página inicial / Utilizadores / Listar</span></li>
            </ol>
        </div>
    </header>

    <div class="row">
        <div class="col-lg-12">
            <section class="card">
                <header class="card-header"><h2 class="card-title">Utilizadores</h2></header>
                <div class="card-body">
                    <table class="table table-bordered table-striped mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $idAdminSessao = $_SESSION["iduser"];

                    if ($idAdminSessao == 0) {
                        // Admin root vê todos os utilizadores
                        $res = mysqli_query($link, "SELECT * FROM utilizadores ORDER BY id_utilizador");
                    } else {
                        // Outros admins só vêem atletas e treinadores
                        $res = mysqli_query($link, "SELECT * FROM utilizadores WHERE perfil <> 'admin' ORDER BY id_utilizador");
                    }

                    while ($row = mysqli_fetch_assoc($res)) {
                        echo "<tr>";
                        echo "<td>{$row['id_utilizador']}</td>";
                        echo "<td>{$row['nome']}</td>";
                        echo "<td>{$row['email']}</td>";
                        echo "<td>{$row['telefone']}</td>";
                        echo "<td>{$row['perfil']}</td>";
                        echo "<td>";
                        echo "<a href='editar.php?id_utilizador={$row['id_utilizador']}' class='btn btn-sm btn-primary'>Editar <i class='fa fa-pencil'></i></a>";
                        echo "</td>";
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
