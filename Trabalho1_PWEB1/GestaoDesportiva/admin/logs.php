<?php
session_start();
include "../DBConnection.php";

// Verifica sessão e perfil
if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != 'admin') {
    header("Location: ../index.php");
    exit;
}

// Se o botão reset for clicado
if (isset($_POST['reset_logs'])) {
    mysqli_query($link, "TRUNCATE TABLE logs");
    header("Location: logs.php");
    exit;
}

// Consulta para obter os logs (ordenados do mais antigo para o mais recente)
$sql = "SELECT id_log, user, acao, 
               DATE_FORMAT(DATE_SUB(datahora, INTERVAL 1 HOUR), '%d/%m/%Y %H:%i:%s') AS datahora 
        FROM logs 
        ORDER BY id_log ASC";
$result = mysqli_query($link, $sql);
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Logs do Sistema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="../vendor/animate/animate.css">
    <link rel="stylesheet" href="../vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="../vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="../css/theme.css" />
    <link rel="stylesheet" href="../css/skins/default.css" />
    <link rel="stylesheet" href="../css/custom.css">
    <script src="../vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body">
    <?php include '../header.php'; ?>
    <div class="inner-wrapper">
        <?php include 'menuadmin.php'; ?>

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>Logs do sistema</h2>
                <div class="right-wrapper text-right">
                    <ol class="breadcrumbs">
                        <li><a href="../inicio.php"><i class="fa fa-home"></i></a></li>
                        <li><span>Logs</span></li>
                        <li><span>Logs do sistema</span></li>
                    </ol>
                </div>
            </header>

            <div class="row">
                <div class="col-lg-12">
                    <section class="card">
                        <header class="card-header">
                            <h2 class="card-title">Registos de Ação</h2>
                        </header>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Utilizador</th>
                                            <th>Ação</th>
                                            <th>Data e Hora</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (mysqli_num_rows($result) > 0): ?>
                                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                                <tr>
                                                    <td><?= $row['id_log'] ?></td>
                                                    <td><?= htmlspecialchars($row['user']) ?></td>
                                                    <td><?= htmlspecialchars($row['acao']) ?></td>
                                                    <td><?= $row['datahora'] ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="4" class="text-center">Sem registos de log</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($_SESSION["iduser"] == 0): ?>
                                <!-- Botão de Reset só visível para admin com ID 0 -->
                                <form method="post" onsubmit="return confirmarReset();">
                                    <div class="text-right mt-3">
                                        <button type="submit" name="reset_logs" class="btn btn-danger">
                                            <i class="fa fa-trash"></i> Limpar Todos os Logs
                                        </button>
                                    </div>
                                </form>
                            <?php endif; ?>

                        </div>
                    </section>
                </div>
            </div>
        </section>
    </div>
</section>

<!-- JS -->
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

<!-- Script de confirmação -->
<script>
function confirmarReset() {
    return confirm("Tem a certeza que pretende eliminar todos os logs do sistema?");
}
</script>
</body>
</html>
