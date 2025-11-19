<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != 'admin') {
    header("Location: ../index.php");
    exit;
}
if (!isset($_GET["id_modalidade"])) {
    echo "ID em falta.";
    exit;
}

$id = $_GET["id_modalidade"];
$res = mysqli_query($link, "SELECT * FROM modalidade WHERE id_modalidade = $id");
if (!$res || mysqli_num_rows($res) == 0) {
    echo "Modalidade não encontrada.";
    exit;
}
$row = mysqli_fetch_assoc($res);

$msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST["nome"])) {
    $nome = mysqli_real_escape_string($link, $_POST["nome"]);
    $update = mysqli_query($link, "UPDATE modalidade SET nome = '$nome' WHERE id_modalidade = $id");

    if ($update) {
        $msg = "<div class='alert alert-success'>Modalidade atualizada com sucesso.</div>";
        echo "<script>
                setTimeout(function(){
                    window.location.href = 'listar.php';
                }, 3000);
              </script>";

        $acao = $_SESSION["nome"] . " atualizou modalidade: $nome (ID $id)";
        $datahora = date("Y-m-d H:i:s");
        $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
        $stmt_log = mysqli_prepare($link, $sql_log);
        mysqli_stmt_bind_param($stmt_log, "sss", $_SESSION["nome"], $acao, $datahora);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);
    } else {
        $msg = "<div class='alert alert-danger'>Erro ao atualizar: " . mysqli_error($link) . "</div>";
    }
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Modalidade</title>
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
<div class="row justify-content-center">
    <div class="col-lg-6 mt-5">
        <section class="card">
            <div class="card-body">
                <?= $msg ?>
                <form method="post">
                    <div class="form-group">
                        <label>ID:</label>
                        <input type="text" class="form-control" value="<?= $row['id_modalidade'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Nome:</label>
                        <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($row['nome']) ?>" required>
                    </div>
                    <div class="form-group text-right">
                        <a href="listar.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Voltar</a>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
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

