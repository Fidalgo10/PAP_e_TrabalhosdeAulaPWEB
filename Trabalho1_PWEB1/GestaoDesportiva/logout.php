<?php
session_start();
include 'DBConnection.php';

// Registar log se existir sessão ativa
if (isset($_SESSION['nome'])) {
    $utilizador = $_SESSION['nome'];
    $acao = "$utilizador terminou sessão no site";
    $datahora = date("Y-m-d H:i:s");

    $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
    $stmt_log = mysqli_prepare($link, $sql_log);
    if ($stmt_log) {
        mysqli_stmt_bind_param($stmt_log, "sss", $utilizador, $acao, $datahora);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);
    }
}

// Limpar e destruir a sessão
session_unset();  // remove todas as variáveis da sessão
session_destroy(); // termina a sessão

// Redirecionar para o index
header("Location: index.php");
exit;
