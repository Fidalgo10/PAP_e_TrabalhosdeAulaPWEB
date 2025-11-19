<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"]) || ($_SESSION["perfil"] != 'admin' && $_SESSION["perfil"] != 'treinador')) {
    header("Location: ../index.php");
    exit;
}

$perfil = $_SESSION["perfil"];
$id_util_logado = $_SESSION["iduser"];
$msg = "";

// Obter dados do treinador se necessário
if ($perfil === 'treinador') {
    $res_treinador = mysqli_query($link, "SELECT id_treinador, id_modalidade FROM treinadores WHERE id_utilizador = $id_util_logado");
    $dados_treinador = mysqli_fetch_assoc($res_treinador);
    $id_treinador = $dados_treinador['id_treinador'];
    $id_modalidade = $dados_treinador['id_modalidade'];

    // Obter nome da modalidade
    $res_nome = mysqli_query($link, "SELECT nome FROM modalidade WHERE id_modalidade = $id_modalidade");
    $row_nome = mysqli_fetch_assoc($res_nome);
    $nome_modalidade = $row_nome['nome'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_utilizador = $_POST["id_utilizador"];
    $id_modalidade = ($perfil === 'admin') ? $_POST["id_modalidade"] : $id_modalidade;
    $id_treinador = ($perfil === 'admin') ? $_POST["id_treinador"] : $id_treinador;
    $idade = $_POST["idade"];

    $sql = "INSERT INTO atletas (id_utilizador, id_modalidade, id_treinador, idade) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "iiii", $id_utilizador, $id_modalidade, $id_treinador, $idade);
        if (mysqli_stmt_execute($stmt)) {
            $msg = "<div class='alert alert-success'>Atleta associado com sucesso.</div>";
            echo "<script>setTimeout(() => location.href='listar.php', 3000);</script>";

            $acao = $_SESSION["nome"] . " associou atleta com ID utilizador $id_utilizador";
            $datahora = date("Y-m-d H:i:s");
            $log = mysqli_prepare($link, "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)");
            mysqli_stmt_bind_param($log, "sss", $_SESSION["nome"], $acao, $datahora);
            mysqli_stmt_execute($log);
            mysqli_stmt_close($log);
        } else {
            $msg = "<div class='alert alert-danger'>Erro: " . mysqli_error($link) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Associar Atleta</title>
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
if ($perfil === 'admin') {
    include '../admin/menuadmin.php';
} else {
    include '../treinadores/menutreinador.php';
}
?>

<section role="main" class="content-body">
            <header class="page-header">
                <h2>Associar Atleta</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Página inicial / Atletas / Associar Atleta</span></li>
                    </ol>
                </div>
            </header>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <?= $msg ?>
            <?php if ($perfil === 'treinador'): ?>
                <div class="mb-3 text-right">
                    <a href="../atletas/criar_atleta.php" class="btn btn-success">Criar novo utilizador atleta</a>
                </div>
            <?php endif; ?>

            <section class="card">
                <div class="card-body">
                    <form method="post">
                        <div class="form-group">
                            <label>Utilizador (Atleta):</label>
                            <select name="id_utilizador" class="form-control" required>
                                <option value="">-- Selecionar --</option>
                                <?php
                                $res = mysqli_query($link, "SELECT id_utilizador, nome FROM utilizadores WHERE perfil = 'atleta'");
                                while ($row = mysqli_fetch_assoc($res)) {
                                    echo "<option value='{$row['id_utilizador']}'>{$row['nome']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <?php if ($perfil === 'admin'): ?>
                            <div class="form-group">
                                <label>Modalidade:</label>
                                <select name="id_modalidade" class="form-control" required>
                                    <option value="">-- Selecionar --</option>
                                    <?php
                                    $res = mysqli_query($link, "SELECT id_modalidade, nome FROM modalidade");
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='{$row['id_modalidade']}'>{$row['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Treinador:</label>
                                <select name="id_treinador" class="form-control" required>
                                    <option value="">-- Selecionar --</option>
                                    <?php
                                    $res = mysqli_query($link, "
                                        SELECT t.id_treinador, u.nome 
                                        FROM treinadores t
                                        JOIN utilizadores u ON t.id_utilizador = u.id_utilizador
                                    ");
                                    while ($row = mysqli_fetch_assoc($res)) {
                                        echo "<option value='{$row['id_treinador']}'>{$row['nome']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="id_modalidade" value="<?= $id_modalidade ?>">
                            <input type="hidden" name="id_treinador" value="<?= $id_treinador ?>">
                            <div class="form-group">
                                <label>Modalidade:</label>
                                <input class="form-control" value="<?= htmlspecialchars($nome_modalidade) ?>" disabled>
                            </div>
                            <div class="form-group">
                                <label>Treinador:</label>
                                <input class="form-control" value="<?= $_SESSION["nome"] ?>" disabled>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Idade:</label>
                            <input type="number" name="idade" class="form-control" min="1" required>
                        </div>
                        <div class="form-group text-right">
                            <input type="submit" class="btn btn-primary" value="Associar">
                        </div>
                    </form>
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
