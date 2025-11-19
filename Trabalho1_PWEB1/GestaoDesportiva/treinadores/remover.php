<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Desassociar Treinador</title>
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
<?php include '../admin/menuadmin.php'; ?>

<section role="main" class="content-body">
            <header class="page-header">
                <h2>Desassociar Treinador</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Página inicial / Treinadores / Desassociar Treinador</span></li>
                    </ol>
                </div>
            </header>
    <section class="card">
        <div class="card-body">
            <form method="post" onsubmit="return confirm('Tem a certeza que pretende remover este treinador?');">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Selecione o treinador:</label>
                    <div class="col-sm-8">
                        <select name="id_treinador" class="form-control" required>
                            <option value="">-- Escolher --</option>
                            <?php
                            $res = mysqli_query($link, "
                                SELECT t.id_treinador, u.nome AS nome_utilizador, m.nome AS modalidade
                                FROM treinadores t
                                JOIN utilizadores u ON t.id_utilizador = u.id_utilizador
                                JOIN modalidade m ON t.id_modalidade = m.id_modalidade
                                ORDER BY u.nome
                            ");
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id_treinador']}'>[{$row['id_treinador']}] {$row['nome_utilizador']} - {$row['modalidade']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8 offset-sm-4">
                        <button type="submit" class="btn btn-danger">Apagar</button>
                    </div>
                </div>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_treinador"])) {
                $id = $_POST["id_treinador"];

                $resInfo = mysqli_query($link, "
                    SELECT t.id_treinador, u.nome AS nome_utilizador, m.nome AS modalidade
                    FROM treinadores t
                    JOIN utilizadores u ON t.id_utilizador = u.id_utilizador
                    JOIN modalidade m ON t.id_modalidade = m.id_modalidade
                    WHERE t.id_treinador = $id
                ");
                $info = mysqli_fetch_assoc($resInfo);

                $del = mysqli_query($link, "DELETE FROM treinadores WHERE id_treinador = $id");
                if ($del) {
                    echo "<p class='alert alert-success mt-3'>Treinador desassociado com sucesso. A página será atualizada...</p>";
                    echo "<script>setTimeout(() => location.href='remover.php', 3000);</script>";

                    $acao = $_SESSION["nome"] . " desassociou o treinador: {$info['nome_utilizador']} ({$info['modalidade']})";
                    $datahora = date("Y-m-d H:i:s");
                    $log = mysqli_prepare($link, "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)");
                    mysqli_stmt_bind_param($log, "sss", $_SESSION["nome"], $acao, $datahora);
                    mysqli_stmt_execute($log);
                    mysqli_stmt_close($log);
                } else {
                    echo "<p class='alert alert-danger mt-3'>Erro ao remover treinador.</p>";
                }
            }
            ?>
        </div>
    </section>
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