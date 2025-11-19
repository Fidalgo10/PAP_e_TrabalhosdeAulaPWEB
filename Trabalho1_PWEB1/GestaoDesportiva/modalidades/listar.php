<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"])) {
    header("Location: index.php");
    exit;
}

$perfil = $_SESSION["perfil"];

// Inicializar com -1 (não há modalidade destacada por defeito)
$modalidadeDoUtilizador = -1;

if ($perfil == 'treinador') {
    $iduser = $_SESSION["iduser"];
    $res_modalidade = mysqli_query($link, "SELECT id_modalidade FROM treinadores WHERE id_utilizador = $iduser");
    if ($row_mod = mysqli_fetch_assoc($res_modalidade)) {
        $modalidadeDoUtilizador = $row_mod["id_modalidade"];
    }
} elseif ($perfil == 'atleta') {
    $iduser = $_SESSION["iduser"];
    $res_modalidade = mysqli_query($link, "SELECT id_modalidade FROM atletas WHERE id_utilizador = $iduser");
    if ($row_mod = mysqli_fetch_assoc($res_modalidade)) {
        $modalidadeDoUtilizador = $row_mod["id_modalidade"];
    }
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Listar Modalidades</title>
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
    } else {
        include '../atletas/menuatleta.php';
    }
    ?>

<section role="main" class="content-body">
    <header class="page-header">
        <h2>Listar Modalidades</h2>
        <div class="right-wrapper text-right">
            <ol class="breadcrumbs">
                <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                <li><span>Página inicial / Modalidades / Listar</span></li>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $qry = "SELECT * FROM modalidade ORDER BY id_modalidade";
                            $result = mysqli_query($link, $qry);
                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $classeLinha = ($row['id_modalidade'] == $modalidadeDoUtilizador) ? "table-success" : "";
                                    echo "<tr class='$classeLinha'>";
                                    echo "<td>{$row['id_modalidade']}</td>";
                                    echo "<td>{$row['nome']}</td>";
                                    echo "<td>";
                                    if ($perfil == 'admin') {
                                        echo "<a href='editar.php?id_modalidade={$row['id_modalidade']}' class='btn btn-sm btn-primary'>Editar <i class='fa fa-pencil'></i></a>";
                                    } else {
                                        echo "Sem Permissão";
                                    }
                                    echo "</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='3' class='text-center'>Não existem modalidades registadas.</td></tr>";
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
