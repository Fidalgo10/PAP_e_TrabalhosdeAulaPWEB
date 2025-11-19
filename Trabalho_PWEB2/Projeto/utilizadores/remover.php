<?php
session_start();
include "../DBConnection.php";

// Só administradores podem aceder
if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != "administrador") {
    header("Location: ../signin.php");
    exit;
}
// Obter perfil do utilizador
$perfil = $_SESSION['perfil'] ?? 'utilizador';
$mensagem = "";

// Processar remoção
if (isset($_POST['remover_id'])) {
    $idUser = intval($_POST['remover_id']);

    if ($idUser != $_SESSION['iduser']) { // Não pode remover-se a si próprio
        $stmt = mysqli_prepare($link, "SELECT nome, perfil FROM utilizadores WHERE id = ?");
        mysqli_stmt_bind_param($stmt, "i", $idUser);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if ($user) {
            // Regras de permissão
            $podeRemover = false;
            if ($_SESSION['iduser'] == 0) {
                // Admin master pode remover todos exceto ele próprio
                $podeRemover = true;
            } elseif ($_SESSION['perfil'] == "administrador" && $user['perfil'] == "utilizador") {
                // Admin normal só pode remover utilizadores
                $podeRemover = true;
            }

            if ($podeRemover) {
                $stmtDel = mysqli_prepare($link, "DELETE FROM utilizadores WHERE id = ?");
                mysqli_stmt_bind_param($stmtDel, "i", $idUser);
                if (mysqli_stmt_execute($stmtDel)) {
                    $mensagem = "<div class='alert alert-success'>Utilizador <strong>" . htmlspecialchars($user['nome']) . "</strong> removido com sucesso.</div>";

                    // Inserir log
                    $acao = "Remoção de utilizador";
                    $detalhes = $_SESSION["nome"] . " removeu o utilizador " . $user['nome'] . " (" . $user['perfil'] . ")";
                    $stmt_log = mysqli_prepare($link, "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
                    if ($stmt_log) {
                        mysqli_stmt_bind_param($stmt_log, "iss", $_SESSION["iduser"], $acao, $detalhes);
                        mysqli_stmt_execute($stmt_log);
                        mysqli_stmt_close($stmt_log);
                    }
                } else {
                    $mensagem = "<div class='alert alert-danger'>Erro ao remover: " . mysqli_error($link) . "</div>";
                }
                mysqli_stmt_close($stmtDel);
            } else {
                $mensagem = "<div class='alert alert-warning'>Não tem permissão para remover este utilizador.</div>";
            }
        }
    } else {
        $mensagem = "<div class='alert alert-warning'>Não pode remover-se a si próprio.</div>";
    }
}

// Buscar utilizadores permitidos para remoção
if ($_SESSION['iduser'] == 0) {
    // Admin master vê todos exceto ele próprio
    $sql = "SELECT id, nome, perfil FROM utilizadores WHERE id != 0 ORDER BY perfil DESC, nome ASC";
    $stmt = mysqli_prepare($link, $sql);
} else {
    // Admin normal vê apenas utilizadores, exceto ele próprio
    $sql = "SELECT id, nome, perfil FROM utilizadores WHERE perfil = 'utilizador' AND id != ? ORDER BY nome ASC";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['iduser']);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Remover Utilizadores - Gestor de Tarefas</title>
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
        $lightColor   = ($perfil === 'administrador') ? '#F3F6F9' : '#F3F6F9';
        $darkColor    = ($perfil === 'administrador') ? '#191C24' : '#191C24';
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
                class="nav-item nav-link d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" aria-expanded="false">
                    <span><i class="fa fa-check-square me-2"></i>Tarefas</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse ps-4" id="tarefasSubmenu">
                    <a href="../tarefas/inserir_tarefas.php" class="nav-item nav-link">Inserir Tarefa</a>
                    <a href="../tarefas/listar_tarefas.php" class="nav-item nav-link">Listar Tarefas</a>
                    <a href="../tarefas/remover_tarefas.php" class="nav-item nav-link">Remover Tarefas</a>
                </div>

                <!-- Menu Utilizadores -->
                <?php if ($perfil == 'administrador'): ?>
                <a href="#utilizadoresSubmenu" 
                   class="nav-item nav-link active d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse" aria-expanded="true">
                    <span><i class="fa fa-users me-2"></i>Utilizadores</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse show ps-4" id="utilizadoresSubmenu">
                    <a href="inserir.php" class="nav-item nav-link">Inserir Utilizador</a>
                    <a href="listar.php" class="nav-item nav-link">Listar Utilizadores</a>
                    <a href="remover.php" class="nav-item nav-link active">Eliminar Utilizador</a>
                </div>
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
                        // Definir a imagem do utilizador
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
                <div class="col-lg-6">

                    <?= $mensagem ?>

                    <div class="bg-light rounded p-4">
                        <h3 class="mb-4">Remover Utilizador</h3>

                        <form method="post" id="removerForm">
                            <div class="mb-3">
                                <label class="form-label">Escolha o utilizador</label>
                                <select name="remover_id" class="form-select" required>
                                    <option value="" selected disabled>Selecione...</option>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                        <option value="<?= $row['id'] ?>">
                                            <?= htmlspecialchars($row['nome']) ?> (<?= htmlspecialchars($row['perfil']) ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-danger">Remover</button>
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

<!-- JavaScript Libraries -->
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
    // Confirmação antes de remover
    document.getElementById('removerForm').addEventListener('submit', function(e) {
        if (!confirm("Tem a certeza que pretende remover este utilizador?")) {
            e.preventDefault();
        }
    });
    document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('open');
});
</script>
</body>
</html>
