<?php
session_start();
include "DBConnection.php";

if (!isset($_SESSION["iduser"])) {
    header("Location: index.php");
    exit;
}

$iduser = $_SESSION["iduser"];
$perfil = $_SESSION["perfil"];
$msgErro = "";
$msgSucesso = "";

// Buscar dados do utilizador
$query = "SELECT * FROM utilizadores WHERE id_utilizador = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $iduser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Utilizador não encontrado.";
    exit;
}
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Buscar idade do atleta se for atleta
$idade = "";
if ($perfil == "atleta") {
    $res = mysqli_query($link, "SELECT idade FROM atletas WHERE id_utilizador = $iduser");
    if ($res && mysqli_num_rows($res) > 0) {
        $linha = mysqli_fetch_assoc($res);
        $idade = $linha["idade"];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = mysqli_real_escape_string($link, $_POST["nome"]);
    $email = mysqli_real_escape_string($link, $_POST["email"]);
    $telefone = mysqli_real_escape_string($link, $_POST["telefone"]);
    $pass = mysqli_real_escape_string($link, $_POST["pass"]);

    $sql = "UPDATE utilizadores SET nome=?, email=?, telefone=?, pass=? WHERE id_utilizador=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $telefone, $pass, $iduser);

    if (mysqli_stmt_execute($stmt)) {
        // Se for atleta, atualizar também a idade
        if ($perfil == "atleta" && isset($_POST["idade"])) {
            $nova_idade = intval($_POST["idade"]);
            $stmt2 = mysqli_prepare($link, "UPDATE atletas SET idade = ? WHERE id_utilizador = ?");
            mysqli_stmt_bind_param($stmt2, "ii", $nova_idade, $iduser);
            mysqli_stmt_execute($stmt2);
            mysqli_stmt_close($stmt2);
        }

        $msgSucesso = "Dados atualizados com sucesso.";
        $_SESSION["nome"] = $nome;

        $acao = "$nome atualizou os seus dados pessoais";
        $datahora = date("Y-m-d H:i:s");
        $stmt_log = mysqli_prepare($link, "INSERT INTO logs (user, acao, datahora) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt_log, "sss", $nome, $acao, $datahora);
        mysqli_stmt_execute($stmt_log);
        mysqli_stmt_close($stmt_log);
    } else {
        $msgErro = "Erro ao atualizar: " . mysqli_error($link);
    }

    mysqli_stmt_close($stmt);
}


$query = "SELECT * FROM utilizadores WHERE id_utilizador = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $iduser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (!$result || mysqli_num_rows($result) == 0) {
    echo "Utilizador não encontrado.";
    exit;
}
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
mysqli_close($link);
?>

<!doctype html>
<html class="fixed" lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Alterar Dados Pessoais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS Porto Admin -->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="css/theme.css" />
    <link rel="stylesheet" href="css/skins/default.css" />
    <link rel="stylesheet" href="css/custom.css" />

    <!-- JS Porto Admin -->
    <script src="vendor/jquery/jquery.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.js"></script>
    <script src="js/theme.js"></script>
    <script src="js/custom.js"></script>
    <script src="js/theme.init.js"></script>
</head>
<body>
<section class="body">
    <div class="row justify-content-center">
        <div class="col-lg-6 mt-5">
            <section class="card">
                <header class="card-header">
                    <img src="img/gestaologo.png" height="54" alt="Isec Admin"/><br><br>
                    <h2 class="card-title">Alteração dos Dados Pessoais</h2>
                </header>
                <div class="card-body">
                    <?php 
                    if (!empty($msgErro)) {
                        echo "<div class='alert alert-danger'>$msgErro</div>";
                    }
                    if (!empty($msgSucesso)) {
                        echo "<div class='alert alert-success'>$msgSucesso</div>";
                        echo "<script>
                                setTimeout(function() {
                                    window.location.href = 'inicio.php';
                                }, 3000);
                              </script>";
                    }
                    ?>
                    <form action="alterardados.php" method="post" onsubmit="return confirmarAlteracao();">
                        <div class="form-group">
                            <label>Nome:</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon"><span class="icon"><i class="fa fa-user"></i></span></span>
                                <input class="form-control" type="text" name="nome" value="<?= htmlspecialchars($row['nome']) ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon"><span class="icon"><i class="fa fa-envelope"></i></span></span>
                                <input class="form-control" type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Telefone:</label>
                            <div class="input-group input-group-icon">
                                <span class="input-group-addon"><span class="icon"><i class="fa fa-phone"></i></span></span>
                                <input class="form-control" type="text" name="telefone" value="<?= htmlspecialchars($row['telefone']) ?>" required>
                            </div>
                        </div>
                        
                        <?php if ($perfil == "atleta"): ?>
                            <div class="form-group">
                                <label>Idade:</label>
                                <div class="input-group input-group-icon">
                                    <span class="input-group-addon"><span class="icon"><i class="fa fa-birthday-cake"></i></span></span>
                                    <input class="form-control" type="number" name="idade" value="<?= htmlspecialchars($idade) ?>" required min="1">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Nova Password:</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-key"></i></span>
                                </div>
                                <input type="password" class="form-control" name="pass" id="passInput" value="<?= htmlspecialchars($row['pass']) ?>" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()" tabindex="-1">
                                        <i class="fa fa-eye" id="eyeIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>


                        <div class="form-group text-right">
                            <a href="inicio.php" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Voltar</a>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
<script>
function confirmarAlteracao() {
    return confirm("Tem a certeza que deseja alterar os seus dados?");
}
</script>
<script>
function togglePassword() {
    const passInput = document.getElementById("passInput");
    const eyeIcon = document.getElementById("eyeIcon");

    if (passInput.type === "password") {
        passInput.type = "text";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        passInput.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>
</body>
</html>
