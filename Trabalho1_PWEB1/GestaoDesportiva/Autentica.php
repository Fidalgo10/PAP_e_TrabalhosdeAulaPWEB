<?php
include 'DBConnection.php';
session_start();

if (!isset($_POST['email'], $_POST['pass'])) {
    header("Location: index.php?erro=1");
    exit();
}

$email = $_POST['email'];
$pass = $_POST['pass'];

$sql = "SELECT id_utilizador, nome, perfil FROM utilizadores WHERE email = ? AND pass = ?";
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $email, $pass);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $nome, $perfil);
        mysqli_stmt_fetch($stmt);

        $_SESSION['iduser'] = $id;
        $_SESSION['nome'] = $nome;
        $_SESSION['perfil'] = $perfil;
        $_SESSION['erro'] = 0;

        // Inserir log
        $acao = "$nome fez login no site";
        $datahora = date("Y-m-d H:i:s");

        $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
        $stmt_log = mysqli_prepare($link, $sql_log);
        if ($stmt_log) {
            mysqli_stmt_bind_param($stmt_log, "sss", $nome, $acao, $datahora);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        // Redirecionar conforme o perfil
// Redirecionar diretamente para inicio.php após login bem-sucedido
header("Location: inicio.php");


    } else {
        $_SESSION['erro'] = 1;
        header("Location: index.php?erro=1");
    }

    mysqli_stmt_close($stmt);
} else {
    die("Erro ao executar a consulta SQL");
}

mysqli_close($link);
