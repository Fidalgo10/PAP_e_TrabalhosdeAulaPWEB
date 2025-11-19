<?php
session_start();
if (!isset($_SESSION['iduser'])) {
    header("Location: signin.php");
    exit();
}

include 'DBConnection.php';

$idUser = $_SESSION['iduser'];

// Buscar dados do utilizador
$sql = "SELECT * FROM utilizadores WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $idUser);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    echo "Utilizador não encontrado.";
    exit;
}

// Processar atualização se submetido
$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome     = $_POST["nome"];
    $email    = $_POST["email"];
    $telefone = $_POST["telefone"];
    $password = $_POST["password"];

    if (!empty($password)) {
        $hashedPass = password_hash($password, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE utilizadores SET nome=?, email=?, telefone=?, password=? WHERE id=?";
        $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "ssssi", $nome, $email, $telefone, $hashedPass, $idUser);
    } else {
        $sqlUpdate = "UPDATE utilizadores SET nome=?, email=?, telefone=? WHERE id=?";
        $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
        mysqli_stmt_bind_param($stmtUpdate, "sssi", $nome, $email, $telefone, $idUser);
    }

    if (mysqli_stmt_execute($stmtUpdate)) {
        $mensagem = "<div class='alert alert-success'>Perfil atualizado com sucesso.</div>";

        // Inserir log
        $acao = "Edição de perfil";
        $detalhes = $_SESSION["nome"] . " atualizou o seu perfil";
        $stmt_log = mysqli_prepare($link, "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
        if ($stmt_log) {
            mysqli_stmt_bind_param($stmt_log, "iss", $_SESSION["iduser"], $acao, $detalhes);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        $_SESSION['nome'] = $nome;
    } else {
        $mensagem = "<div class='alert alert-danger'>Erro: " . mysqli_error($link) . "</div>";
    }
    mysqli_stmt_close($stmtUpdate);
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Perfil - Gestor de Tarefas</title>
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
<div class="container-xxl position-relative bg-white d-flex flex-column min-vh-100">

    <!-- Conteúdo -->
    <div class="container-fluid pt-4 px-4 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <!-- Botão voltar -->
                <a href="index.php" class="btn btn-secondary mb-3"><i class="fa fa-arrow-left me-2"></i>Voltar</a>

                <?= $mensagem ?>

                <div class="bg-light rounded p-4">
                    <h3 class="mb-4">Meu Perfil</h3>

                    <form method="post" id="perfilForm">
                        <div class="mb-3">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($user['telefone']) ?>" readonly>
                        </div>
                        <div class="mb-3 position-relative">
                            <label class="form-label">Palavra-passe</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" readonly>
                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"
                                style="position:absolute; top:50%; right:15px; transform:translateY(-50%); cursor:pointer;"></span>
                            <small class="text-muted">Deixe vazio se não quiser alterar</small>
                        </div>
                        <div class="text-end">
                            <button type="button" class="btn btn-warning" id="editarBtn">Editar</button>
                            <button type="submit" class="btn btn-primary d-none" id="guardarBtn">Guardar Alterações</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center p-3 mt-auto">
        <small>Gestor de Tarefas — Projeto Programação Web II</small>
    </footer>
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
    <script src="js/main.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
    <script>
        document.querySelector('.toggle-password').addEventListener('click', function () {
            const input = document.querySelector('#password');
            if (input.type === 'password') {
                input.type = 'text';
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    </script>
<script>
    document.getElementById('editarBtn').addEventListener('click', function() {
        document.querySelectorAll('#perfilForm input').forEach(input => input.removeAttribute('readonly'));
        document.getElementById('guardarBtn').classList.remove('d-none');
        this.classList.add('d-none');
    });

    document.getElementById('perfilForm').addEventListener('submit', function(e) {
        if (!confirm("Tem a certeza que pretende alterar o seu perfil?")) {
            e.preventDefault();
        }
    });
</script>
</body>
</html>
