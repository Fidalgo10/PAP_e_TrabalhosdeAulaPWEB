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
    <title>Inserir Utilizador</title>
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
                <h2>Inserir Utilizador</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Página inicial / Utilizadores / Inserir</span></li>
                    </ol>
                </div>
            </header>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <section class="card">
                <header class="card-header"><h2 class="card-title">Formulário de Utilizador</h2></header>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Telefone</label>
                            <input type="text" name="telefone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Palavra-passe</label>
                            <input type="password" name="pass" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Perfil</label>
                            <select name="perfil" class="form-control" required>
                                <option value="admin">Administrador</option>
                                <option value="treinador">Treinador</option>
                                <option value="atleta">Atleta</option>
                            </select>
                        </div>
                        <div class="form-group text-right">
                            <input type="reset" class="btn btn-secondary" value="Limpar">
                            <input type="submit" class="btn btn-primary" value="Inserir">
                        </div>
                    </form>

                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $nome = $_POST["nome"];
                        $email = $_POST["email"];
                        $telefone = $_POST["telefone"];
                        $pass = $_POST["pass"];
                        $perfil = $_POST["perfil"];

                        $sql = "INSERT INTO utilizadores (nome, email, telefone, pass, perfil) VALUES (?, ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($link, $sql);
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "sssss", $nome, $email, $telefone, $pass, $perfil);
                            if (mysqli_stmt_execute($stmt)) {
                                echo "<p class='alert alert-success mt-3'>Utilizador inserido com sucesso.</p>";

                                $acao = $_SESSION["nome"] . " inseriu utilizador $nome ($perfil)";
                                $datahora = date("Y-m-d H:i:s");
                                $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
                                $stmt_log = mysqli_prepare($link, $sql_log);
                                mysqli_stmt_bind_param($stmt_log, "sss", $_SESSION["nome"], $acao, $datahora);
                                mysqli_stmt_execute($stmt_log);
                                mysqli_stmt_close($stmt_log);
                            } else {
                                echo "<p class='alert alert-danger mt-3'>Erro ao inserir: " . mysqli_error($link) . "</p>";
                            }
                            mysqli_stmt_close($stmt);
                        }
                    }
                    ?>
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
