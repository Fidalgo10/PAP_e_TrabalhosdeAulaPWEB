<?php
session_start();
include "../DBConnection.php";

// Só utilizadores autenticados podem aceder
if (!isset($_SESSION["iduser"])) {
    header("Location: ../signin.php");
    exit;
}

$perfil   = $_SESSION['perfil'];
$idUser   = $_SESSION['iduser'];

// Buscar todos os utilizadores apenas se for admin
$result_users = [];
if ($perfil == 'administrador') {
    $sql_users = "SELECT id, nome FROM utilizadores ORDER BY nome ASC";
    $result_users = mysqli_query($link, $sql_users);
}

// Processar formulário
$mensagem = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo      = $_POST["titulo"];
    $descricao   = $_POST["descricao"];
    $data_limite = $_POST["data_limite"];
    $status      = $_POST["status"];
    $assigned_to = !empty($_POST["assigned_to"]) ? intval($_POST["assigned_to"]) : $idUser;

    $sql = "INSERT INTO tarefas (titulo, descricao, status, assigned_to, created_by, data_limite, criado) 
            VALUES (?, ?, ?, ?, ?, ?, NOW())";
    $stmt = mysqli_prepare($link, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssiss", $titulo, $descricao, $status, $assigned_to, $idUser, $data_limite);
        if (mysqli_stmt_execute($stmt)) {
            $mensagem = "<div class='alert alert-success'>Tarefa inserida com sucesso.</div>";

            // Inserir log
            $acao     = "Inserção de tarefa";
            $detalhes = $_SESSION["nome"] . " inseriu a tarefa '$titulo'";
            $sql_log  = "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)";
            $stmt_log = mysqli_prepare($link, $sql_log);
            if ($stmt_log) {
                mysqli_stmt_bind_param($stmt_log, "iss", $idUser, $acao, $detalhes);
                mysqli_stmt_execute($stmt_log);
                mysqli_stmt_close($stmt_log);
            }
        } else {
            $mensagem = "<div class='alert alert-danger'>Erro: " . mysqli_error($link) . "</div>";
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Inserir Tarefa - Gestor de Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon -->
    <link href="../img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="../css/style.css" rel="stylesheet">

    <?php
        $primaryColor = ($perfil === 'administrador') ? '#b91010' : '#009CFF';
        $lightColor   = '#F3F6F9';
        $darkColor    = '#191C24';
    ?>
    <style>
        :root {
            --primary: <?= $primaryColor ?>;
            --light: <?= $lightColor ?>;
            --dark: <?= $darkColor ?>;
        }
    </style>
</head>
<body>
<div class="container-xxl position-relative bg-white d-flex p-0">

    <!-- Sidebar -->
    <div class="sidebar pe-4 pb-3">
        <nav class="navbar bg-light navbar-light">
            <a href="../index.php" class="navbar-brand mx-4 mb-3">
                <img src="../img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" alt="Logo" style="width: 80%; height: 100%;">
            </a>
            <div class="navbar-nav w-100">
                <a href="../index.php" class="nav-item nav-link">
                    <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                </a>

                <!-- Menu Tarefas -->
                <a href="#tarefasSubmenu" 
                class="nav-item nav-link active d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" aria-expanded="true">
                    <span><i class="fa fa-check-square me-2"></i>Tarefas</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse show ps-4" id="tarefasSubmenu">
                    <a href="inserir_tarefas.php" class="nav-item nav-link active">Inserir Tarefa</a>
                    <a href="listar_tarefas.php" class="nav-item nav-link">Listar Tarefas</a>
                    <a href="remover_tarefas.php" class="nav-item nav-link">Remover Tarefas</a>
                </div>

                <?php if ($perfil == 'administrador'): ?>
                    <!-- Menu Utilizadores -->
                    <a href="#utilizadoresSubmenu" 
                    class="nav-item nav-link d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" aria-expanded="false">
                        <span><i class="fa fa-users me-2"></i>Utilizadores</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>
                    <div class="collapse ps-4" id="utilizadoresSubmenu">
                        <a href="../utilizadores/inserir.php" class="nav-item nav-link">Inserir Utilizador</a>
                        <a href="../utilizadores/listar.php" class="nav-item nav-link">Listar Utilizadores</a>
                        <a href="../utilizadores/remover.php" class="nav-item nav-link">Eliminar Utilizador</a>
                    </div>

                    <!-- Logs -->
                    <a href="../logs.php" class="nav-item nav-link">
                        <i class="fa fa-history me-2"></i>Logs
                    </a>
                <?php endif; ?>

                <a href="../logout.php" class="nav-item nav-link">
                    <i class="fa fa-sign-out-alt me-2"></i>Sair
                </a>
            </div>
        </nav>
    </div>

    <!-- Content -->
    <div class="content flex-grow-1">

        <!-- Navbar -->
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="../index.php" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-<?= $perfil==='administrador' ? 'danger' : 'primary' ?> mb-0"><i class="fa fa-tasks"></i></h2>
            </a>
            <div class="navbar-nav align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <?php
                        $userImg = ($perfil == 'administrador') ? "../img/admin.png" : "../img/user.png";
                        ?>
                        <img class="rounded-circle me-lg-2" src="<?= $userImg ?>" style="width: 40px; height: 40px;">
                        <span class="d-inline-flex"><?=htmlspecialchars($_SESSION['nome'])?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="../perfil.php" class="dropdown-item">O meu Perfil</a>
                        <a href="../logout.php" class="dropdown-item">Terminar Sessão</a>
                    </div>
                </div>
                <button class="btn btn-<?= $perfil==='administrador'?'danger':'primary' ?> d-lg-none me-2" id="sidebarToggle" style="margin-left: 20px;">
                    <i class="fa fa-bars"></i>
                </button>
            </div>
        </nav>

        <!-- Conteúdo principal -->
        <div class="container-fluid pt-4 px-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    <?= $mensagem ?>

                    <div class="bg-light rounded p-4">
                        <h3 class="mb-4">Inserir Tarefa</h3>
                        <form method="post">
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="titulo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="4"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data Limite</label>
                                <input type="date" name="data_limite" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="pendente">Pendente</option>
                                    <option value="em_progresso">Em Progresso</option>
                                    <option value="concluida">Concluída</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <?php if ($perfil == 'administrador'): ?>
                                    <label class="form-label">Atribuir a</label>
                                    <select name="assigned_to" class="form-select">
                                        <option value="">-- Nenhum --</option>
                                        <?php while($row = mysqli_fetch_assoc($result_users)) { ?>
                                            <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nome']) ?></option>
                                        <?php } ?>
                                    </select>
                                <?php else: ?>
                                    <input type="hidden" name="assigned_to" value="<?= $idUser ?>">
                                <?php endif; ?>
                            </div>
                            <div class="text-end">
                                <button type="reset" class="btn btn-secondary me-2">Limpar</button>
                                <button type="submit" class="btn btn-primary">Inserir</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <footer class="bg-light text-center p-3 mt-auto">
            <small>Gestor de Tarefas — Projeto Programação Web II</small>
        </footer>

    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/chart/chart.min.js"></script>
<script src="../lib/easing/easing.min.js"></script>
<script src="../lib/waypoints/waypoints.min.js"></script>
<script src="../lib/owlcarousel/owl.carousel.min.js"></script>
<script src="../lib/tempusdominus/js/moment.min.js"></script>
<script src="../lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="../lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="../js/main.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('open');
});
</script>
</body>
</html>
