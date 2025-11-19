<?php
include 'DBConnection.php';
session_start();

if (!isset($_POST['email'], $_POST['pass'])) {
    header("Location: signin.php?erro=1");
    exit();
}

$email = $_POST['email'];
$pass  = $_POST['pass'];

// Procurar utilizador pelo email
$sql = "SELECT id, nome, password, perfil FROM utilizadores WHERE email = ?";
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $id, $nome, $hash, $perfil);
        mysqli_stmt_fetch($stmt);

        // Verificar password com hash
        if (password_verify($pass, $hash)) {
            $_SESSION['iduser'] = $id;
            $_SESSION['nome']   = $nome;
            $_SESSION['perfil'] = $perfil; // Guardar perfil na sessão
            $_SESSION['erro']   = 0;

            // Inserir log (utiliza a tabela logs)
            $acao     = "Login efetuado";
            $detalhes = "$nome fez login no sistema";
            $sql_log  = "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($link, $sql_log);
            if ($stmt_log) {
                mysqli_stmt_bind_param($stmt_log, "iss", $id, $acao, $detalhes);
                mysqli_stmt_execute($stmt_log);
                mysqli_stmt_close($stmt_log);
            }

            // Redirecionar para a página inicial
            header("Location: index.php");
            exit();
        }
    }

    // Se não encontrou ou password inválida
    $_SESSION['erro'] = 1;
    header("Location: signin.php?erro=1");
    exit();

    mysqli_stmt_close($stmt);
} else {
    die("Erro ao preparar a consulta SQL");
}

mysqli_close($link);
?>
