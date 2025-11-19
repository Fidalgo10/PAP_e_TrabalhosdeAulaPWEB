<?php
session_start();
include 'DBConnection.php';

// Registar log se existir sessão ativa
if (isset($_SESSION['iduser'], $_SESSION['nome'])) {
    $user_id  = $_SESSION['iduser'];
    $acao     = "Logout efetuado";
    $detalhes = $_SESSION['nome'] . " terminou sessão no sistema";

    $sql_log = "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)";
    $stmt_log = mysqli_prepare($link, $sql_log);
    if ($stmt_log) {
        mysqli_stmt_bind_param($stmt_log, "iss", $user_id, $acao, $detalhes);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);
    }
}

// Limpar e destruir a sessão
session_unset();  
session_destroy(); 

// Redirecionar para o index
header("Location: signin.php");
exit;
?>
