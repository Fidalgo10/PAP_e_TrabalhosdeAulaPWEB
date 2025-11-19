<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"])) {
    header("Location: ../signin.php");
    exit;
}

$idTarefa = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Buscar dados da tarefa
$sql = "SELECT * FROM tarefas WHERE id = ?";
$stmt = mysqli_prepare($link, $sql);
mysqli_stmt_bind_param($stmt, "i", $idTarefa);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$tarefa = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$tarefa) {
    echo "Tarefa não encontrada.";
    exit;
}

$perfil   = $_SESSION['perfil'];
$idUser   = $_SESSION['iduser'];
$mensagem = "";

// Garantir que o utilizador normal só edita tarefas que lhe dizem respeito
if ($perfil != "administrador" && $tarefa['assigned_to'] != $idUser && $tarefa['created_by'] != $idUser) {
    echo "Não tem permissão para editar esta tarefa.";
    exit;
}

// Obter lista de utilizadores (apenas se admin)
$result_users = [];
if ($perfil == "administrador") {
    $sql_users = "SELECT id, nome FROM utilizadores ORDER BY nome ASC";
    $result_users = mysqli_query($link, $sql_users);
}

// Processar edição
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo      = $_POST["titulo"];
    $descricao   = $_POST["descricao"];
    $data_limite = $_POST["data_limite"];
    $status      = $_POST["status"];
    $assigned_to = ($perfil == "administrador" && !empty($_POST["assigned_to"])) ? intval($_POST["assigned_to"]) : $tarefa['assigned_to'];

    $sqlUpdate = "UPDATE tarefas SET titulo=?, descricao=?, data_limite=?, status=?, assigned_to=?, atualizado=NOW() WHERE id=?";
    $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, "ssssii", $titulo, $descricao, $data_limite, $status, $assigned_to, $idTarefa);

    if (mysqli_stmt_execute($stmtUpdate)) {
        $mensagem = "<div class='alert alert-success'>Tarefa atualizada com sucesso.</div>";

        // Inserir log
        $acao     = "Edição de tarefa";
        $detalhes = $_SESSION["nome"] . " editou a tarefa '$titulo'";
        $sql_log  = "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)";
        $stmt_log = mysqli_prepare($link, $sql_log);
        if ($stmt_log) {
            mysqli_stmt_bind_param($stmt_log, "iss", $_SESSION["iduser"], $acao, $detalhes);
            mysqli_stmt_execute($stmt_log);
            mysqli_stmt_close($stmt_log);
        }

        // Atualizar dados locais
        $tarefa['titulo']      = $titulo;
        $tarefa['descricao']   = $descricao;
        $tarefa['data_limite'] = $data_limite;
        $tarefa['status']      = $status;
        $tarefa['assigned_to'] = $assigned_to;
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
    <title>Detalhes da Tarefa - Gestor de Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="../img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="../lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <?php
        $primaryColor = ($perfil === 'administrador') ? '#b91010' : '#009CFF';
    ?>
    <style>
        :root {
            --primary: <?= $primaryColor ?>;
            --light: #F3F6F9;
            --dark: #191C24;
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
                    <a href="inserir_tarefas.php" class="nav-item nav-link">Inserir Tarefa</a>
                    <a href="listar_tarefas.php" class="nav-item nav-link active">Listar Tarefas</a>
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
                        <?php $userImg = ($perfil == 'administrador') ? "../img/admin.png" : "../img/user.png"; ?>
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
                        <h3 class="mb-4">Detalhes da Tarefa</h3>
                        <a href="listar_tarefas.php" class="btn btn-secondary mb-3">
                            <i class="fa fa-arrow-left me-2"></i> Voltar
                        </a>
                        <form method="post" id="detalhesForm">
                            <div class="mb-3">
                                <label class="form-label">Título</label>
                                <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($tarefa['titulo']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrição</label>
                                <textarea name="descricao" class="form-control" rows="4"><?= htmlspecialchars($tarefa['descricao']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Data Limite</label>
                                <input type="date" name="data_limite" class="form-control" value="<?= htmlspecialchars($tarefa['data_limite']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="pendente"   <?= ($tarefa['status']=="pendente") ? "selected" : "" ?>>Pendente</option>
                                    <option value="em_progresso" <?= ($tarefa['status']=="em_progresso") ? "selected" : "" ?>>Em Progresso</option>
                                    <option value="concluida" <?= ($tarefa['status']=="concluida") ? "selected" : "" ?>>Concluída</option>
                                </select>
                            </div>

                            <?php if ($perfil == "administrador"): ?>
                            <div class="mb-3">
                                <label class="form-label">Atribuído a</label>
                                <select name="assigned_to" class="form-select">
                                    <?php mysqli_data_seek($result_users, 0); while($row = mysqli_fetch_assoc($result_users)) { ?>
                                        <option value="<?= $row['id'] ?>" <?= ($tarefa['assigned_to']==$row['id']) ? "selected" : "" ?>>
                                            <?= htmlspecialchars($row['nome']) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Guardar Alterações</button>
                            </div>
                        </form>
                            <script>
                                // Confirmação antes de alterar
                                document.getElementById('detalhesForm').addEventListener('submit', function(e) {
                                    if (!confirm("Tem a certeza que pretende guardar as alterações desta tarefa?")) {
                                        e.preventDefault();
                                    }
                                });

                                // Se existir mensagem de sucesso, redireciona após 3 segundos
                                window.addEventListener('DOMContentLoaded', function () {
                                    const sucesso = document.querySelector('.alert-success');
                                    if (sucesso) {
                                        setTimeout(function() {
                                            window.location.href = "listar_tarefas.php";
                                        }, 3000); // 3000 ms = 3 segundos
                                    }
                                });
                            </script>
                    </div>
                </div>
            </div>
        </div>

        <footer class="bg-light text-center p-3 mt-auto">
            <small>Gestor de Tarefas — Projeto Programação Web II</small>
        </footer>
    </div>
</div>

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
