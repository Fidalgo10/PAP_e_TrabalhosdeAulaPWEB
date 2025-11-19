<?php
session_start();
include 'DBConnection.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome  = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $pass  = $_POST['pass'];

    if (!empty($nome) && !empty($email) && !empty($pass)) {
        // Verificar se o email já existe
        $stmtCheck = mysqli_prepare($link, "SELECT id FROM utilizadores WHERE email=?");
        mysqli_stmt_bind_param($stmtCheck, "s", $email);
        mysqli_stmt_execute($stmtCheck);
        mysqli_stmt_store_result($stmtCheck);

        if (mysqli_stmt_num_rows($stmtCheck) > 0) {
            $mensagem = "<div class='alert alert-warning'>Este email já está registado.</div>";
        } else {
            // Inserir utilizador
            $hashPass = password_hash($pass, PASSWORD_DEFAULT);
            $perfil   = "utilizador"; // sempre utilizador por defeito

            $stmtInsert = mysqli_prepare($link, "INSERT INTO utilizadores (nome, email, password, perfil) VALUES (?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmtInsert, "ssss", $nome, $email, $hashPass, $perfil);

            if (mysqli_stmt_execute($stmtInsert)) {
                $mensagem = "<div class='alert alert-success'>Conta criada com sucesso! A redirecionar para login...</div>";
                // Redirecionar após 3 segundos
                echo "<script>setTimeout(function(){ window.location.href='signin.php'; }, 3000);</script>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao criar conta: ".mysqli_error($link)."</div>";
            }
            mysqli_stmt_close($stmtInsert);
        }
        mysqli_stmt_close($stmtCheck);
    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Criar Conta - Gestor de Tarefas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container-xxl position-relative bg-white d-flex p-0">
    <div class="container-fluid">
        <div class="row h-100 align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="bg-light rounded p-4 p-sm-5 my-4 mx-3">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <h3 class="text-primary"><i class="fa fa-tasks me-2"></i>Gestor</h3>
                        <h3>Criar Conta</h3>
                    </div>

                    <?= $mensagem ?>

                    <form action="" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" name="nome" placeholder="Nome" required>
                            <label>Nome</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" name="email" placeholder="Email" required>
                            <label>Email</label>
                        </div>
                        <div class="form-floating mb-4 position-relative">
                            <input type="password" class="form-control" name="pass" placeholder="Password" required>
                            <label>Password</label>
                        </div>
                        <button type="submit" class="btn btn-primary py-3 w-100 mb-3">Criar Conta</button>
                        <div class="text-center">
                            <p>Já tem conta? <a href="signin.php" class="text-primary fw-bold">Entrar</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script></body>
</html>
