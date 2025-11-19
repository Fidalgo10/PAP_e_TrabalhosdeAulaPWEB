<?php
session_start();
include "DBConnection.php";



// Variáveis de erro
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST["user"]) && !empty($_POST["pass"]) && !empty($_POST["pass_confirm"])) {
        $user = $_POST["user"];
        $pass = $_POST["pass"];
        $pass_confirm = $_POST["pass_confirm"];

        // Verifica se as passwords são iguais
        if ($pass !== $pass_confirm) {
            $errorMessage = "<p class='alert alert-danger mt-3'>A password e a confirmação de password devem ser iguais.</p>";
        } else {
            // Insere o utilizador na base de dados
            $sql = "INSERT INTO utilizadores (user, pass) VALUES (?, ?)";
            $stmt = mysqli_prepare($link, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $user, $pass);
                if (mysqli_stmt_execute($stmt)) {
                    $errorMessage = "<p class='alert alert-success mt-3'>Utilizador inserido com sucesso.</p>";

                    // Registo do log
                    $utilizadorLogado = $user; // neste caso ainda não há sessão, então usa o próprio nome inserido
                    $id_inserido = mysqli_insert_id($link); // obtém o ID gerado
                    $acao = "Criou uma nova conta: $user com ID $id_inserido";
                    $datahora = date("Y-m-d H:i:s");

                    // Inserir log
                    $sql_log = "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)";
                    $stmt_log = mysqli_prepare($link, $sql_log);
                    if ($stmt_log) {
                        mysqli_stmt_bind_param($stmt_log, "sss", $utilizadorLogado, $acao, $datahora);
                        mysqli_stmt_execute($stmt_log);
                        mysqli_stmt_close($stmt_log);
                    }

                } else {
                    $errorMessage = "<p class='alert alert-danger mt-3'>Erro ao inserir: " . mysqli_error($link) . "</p>";
                }
                mysqli_stmt_close($stmt);
            } else {
                $errorMessage = "<p class='alert alert-danger mt-3'>Erro na preparação da consulta.</p>";
            }
        }
    }
}
?>
<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/animate/animate.css">
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
    <link rel="stylesheet" href="css/theme.css" />
    <link rel="stylesheet" href="css/skins/default.css" />
    <link rel="stylesheet" href="css/custom.css">
    <script src="vendor/modernizr/modernizr.js"></script>
</head>
<body>
<section class="body-sign">
    <div class="center-sign">
        <a href="/" class="logo float-left">
            <img src="img/isec.png" height="54" alt="Isec Admin" />
        </a>

        <div class="panel card-sign">
            <div class="card-title-sign mt-3 text-right">
                <h2 class="title text-uppercase font-weight-bold m-0"><i class="fa fa-user mr-1"></i> Sign Up</h2>
            </div>
            <div class="card-body">
                <?php echo $errorMessage; ?>

                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <div class="input-group input-group-icon">
                            <input type="text" name="user" class="form-control form-control-lg" required>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-user"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Password</label>
                        <div class="input-group input-group-icon">
                            <input type="password" name="pass" class="form-control form-control-lg" required>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label>Confirmar Password</label>
                        <div class="input-group input-group-icon">
                            <input type="password" name="pass_confirm" class="form-control form-control-lg" required>
                            <span class="input-group-addon">
                                <span class="icon icon-lg">
                                    <i class="fa fa-lock"></i>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <button type="reset" class="btn btn-secondary mt-2">Limpar</button>
                        </div>
                        <div class="col-sm-6 text-right">
                            <button type="submit" class="btn btn-primary mt-2">Sign Up</button>
                        </div>
                    </div>
                </form>

                <p class="text-center mt-3">Já tens uma conta? <a href="index.php">Entrar</a></p>
            </div>
        </div>

        <p class="text-center mt-3">© Copyright 2025. All Rights Reserved.</p>

    </div>
</section>

<!-- Scripts -->
<script src="vendor/jquery/jquery.js"></script>
<script src="vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="vendor/popper/umd/popper.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.js"></script>
<script src="vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="vendor/common/common.js"></script>
<script src="vendor/nanoscroller/nanoscroller.js"></script>
<script src="vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="vendor/jquery-placeholder/jquery-placeholder.js"></script>
<script src="js/theme.js"></script>
<script src="js/custom.js"></script>
<script src="js/theme.init.js"></script>
</body>
</html>
