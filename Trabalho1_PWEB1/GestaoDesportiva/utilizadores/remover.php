<?php
session_start();
include "../DBConnection.php"; // Caminho RELATIVO correto

if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != 'admin') {
    header("Location: ../index.php");
    exit;
}

$idAdminSessao = $_SESSION["iduser"];
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Remover Utilizador</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Painel de Controlo">
    <meta name="author" content="okler.net">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
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

    <!-- Custom CSS -->
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
        <h2>Remover Utilizador</h2>
        <div class="right-wrapper text-right">
            <ol class="breadcrumbs">
                <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                <li><span>Página inicial / Utilizadores / Remover</span></li>
            </ol>
        </div>
    </header>

    <section class="card">
        <div class="card-body">
            <form action="" method="post" onsubmit="return confirm('Tem a certeza que pretende apagar este utilizador?');">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Escolha o utilizador:</label>
                    <div class="col-sm-8">
                        <select name="id_utilizador" class="form-control">
                            <?php
                            if ($idAdminSessao == 0) {
                                // Admin root: pode ver todos menos ele próprio
                                $res = mysqli_query($link, "SELECT id_utilizador, nome FROM utilizadores WHERE id_utilizador <> 0 ORDER BY id_utilizador");
                            } else {
                                // Outros admins: só podem ver atletas e treinadores
                                $res = mysqli_query($link, "SELECT id_utilizador, nome FROM utilizadores WHERE perfil <> 'admin' ORDER BY id_utilizador");
                            }
                            while ($row = mysqli_fetch_assoc($res)) {
                                echo "<option value='{$row['id_utilizador']}'>{$row['nome']}</option>";
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
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_utilizador"])) {
                $id = intval($_POST["id_utilizador"]);

                // Verificar permissões: se não for admin root, não pode apagar admins
                if ($idAdminSessao != 0) {
                    $verificaPerfil = mysqli_query($link, "SELECT perfil FROM utilizadores WHERE id_utilizador = $id");
                    $dados = mysqli_fetch_assoc($verificaPerfil);
                    if ($dados && $dados["perfil"] == 'admin') {
                        echo "<p class='alert alert-danger mt-3'>Não tem permissão para remover administradores.</p>";
                        exit;
                    }
                }

                // Verifica se o utilizador a eliminar é o próprio
                if ($id == $idAdminSessao) {
                    echo "<p class='alert alert-danger mt-3'>Não pode remover o seu próprio utilizador.</p>";
                    exit;
                }

                // Obter nome para registo no log
                $resInfo = mysqli_query($link, "SELECT nome FROM utilizadores WHERE id_utilizador = $id");
                $info = mysqli_fetch_assoc($resInfo);

                $query = mysqli_query($link, "DELETE FROM utilizadores WHERE id_utilizador = $id");
                if ($query) {
                    echo "<p class='alert alert-success mt-3'>Utilizador removido com sucesso. A página será atualizada...</p>";
                    echo "<script>
                            setTimeout(function() {
                                window.location.href = 'remover.php';
                            }, 3000);
                        </script>";

                    $acao = $_SESSION["nome"] . " removeu o utilizador: " . $info['nome'];
                    $datahora = date("Y-m-d H:i:s");
                    $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
                    $stmt_log = mysqli_prepare($link, $sql_log);
                    mysqli_stmt_bind_param($stmt_log, "sss", $_SESSION["nome"], $acao, $datahora);
                    mysqli_stmt_execute($stmt_log);
                    mysqli_stmt_close($stmt_log);
                } else {
                    echo "<p class='alert alert-danger mt-3'>Erro ao remover utilizador.</p>";
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
<script src="../js/theme.js"></script>
<script src="../js/custom.js"></script>
<script src="../js/theme.init.js"></script>
</body>
</html>
