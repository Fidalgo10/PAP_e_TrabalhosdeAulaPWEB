<?php
session_start();
include "DBConnection.php";

// Só administradores podem aceder
if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != "administrador") {
    header("Location: signin.php");
    exit;
}

$idUser   = $_SESSION['iduser'];
$isMaster = ($idUser == 0);
$mensagem = "";

// Apagar log individual (apenas master)
if ($isMaster && isset($_GET['delete'])) {
    $logId = intval($_GET['delete']);
    $stmt = mysqli_prepare($link, "DELETE FROM logs WHERE id=?");
    mysqli_stmt_bind_param($stmt, "i", $logId);
    if (mysqli_stmt_execute($stmt)) {
        $mensagem = "<div class='alert alert-success'>Log removido com sucesso.</div>";
    } else {
        $mensagem = "<div class='alert alert-danger'>Erro ao remover log.</div>";
    }
    mysqli_stmt_close($stmt);
}

// Reset total (apenas master)
if ($isMaster && isset($_POST['reset_logs'])) {
    mysqli_query($link, "TRUNCATE TABLE logs");
    $mensagem = "<div class='alert alert-warning'>Todos os logs foram removidos. IDs reiniciados.</div>";
}

// Buscar logs
$sql = "SELECT l.id, u.nome AS utilizador, l.action, l.details, l.criado
        FROM logs l
        LEFT JOIN utilizadores u ON l.user_id = u.id
        ORDER BY l.id ASC";
$result = mysqli_query($link, $sql);

$perfil = $_SESSION['perfil'] ?? 'utilizador';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Logs do Sistema - Gestor de Tarefas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" rel="icon">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
<link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

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
            <a href="index.php" class="navbar-brand mx-4 mb-3">
                <img src="img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" alt="Logo" style="width: 80%; height: 100%;">
            </a>
            <div class="navbar-nav w-100">
                <a href="index.php" class="nav-item nav-link">
                    <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="#tarefasSubmenu" 
                   class="nav-item nav-link d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse" aria-expanded="false">
                    <span><i class="fa fa-check-square me-2"></i>Tarefas</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse ps-4" id="tarefasSubmenu">
                    <a href="tarefas/inserir_tarefas.php" class="nav-item nav-link">Inserir Tarefa</a>
                    <a href="tarefas/listar_tarefas.php" class="nav-item nav-link">Listar Tarefas</a>
                    <a href="tarefas/remover_tarefas.php" class="nav-item nav-link">Remover Tarefas</a>
                </div>

                <?php if ($perfil == 'administrador'): ?>
                <a href="#utilizadoresSubmenu" 
                   class="nav-item nav-link d-flex justify-content-between align-items-center"
                   data-bs-toggle="collapse" aria-expanded="false">
                    <span><i class="fa fa-users me-2"></i>Utilizadores</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse ps-4" id="utilizadoresSubmenu">
                    <a href="utilizadores/inserir.php" class="nav-item nav-link">Inserir Utilizador</a>
                    <a href="utilizadores/listar.php" class="nav-item nav-link">Listar Utilizadores</a>
                    <a href="utilizadores/remover.php" class="nav-item nav-link">Eliminar Utilizador</a>
                </div>

                <a href="logs.php" class="nav-item nav-link active">
                    <i class="fa fa-history me-2"></i>Logs
                </a>
                <?php endif; ?>

                <a href="logout.php" class="nav-item nav-link">
                    <i class="fa fa-sign-out-alt me-2"></i>Sair
                </a>
            </div>
        </nav>
    </div>

    <!-- Content -->
    <div class="content flex-grow-1">

        <!-- Navbar -->
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-<?= $perfil==='administrador' ? 'danger' : 'primary' ?> mb-0"><i class="fa fa-tasks"></i></h2>
            </a>
            <div class="navbar-nav align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <?php $userImg = ($perfil == 'administrador') ? "img/admin.png" : "img/user.png"; ?>
                        <img class="rounded-circle me-lg-2" src="<?= $userImg ?>" style="width:40px; height:40px;">
                        <span class="d-inline-flex"><?=htmlspecialchars($_SESSION['nome'])?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                        <a href="perfil.php" class="dropdown-item">O meu Perfil</a>
                        <a href="logout.php" class="dropdown-item">Terminar Sessão</a>
                    </div>
                </div>
                <button class="btn btn-<?= $perfil==='administrador'?'danger':'primary' ?> d-lg-none me-2" id="sidebarToggle" style="margin-left: 20px;">
                    <i class="fa fa-bars"></i>
                </button>                
            </div>
        </nav>

        <!-- Conteúdo principal -->
        <div class="container-fluid pt-4 px-4">
            <div class="row">
                <div class="col-12">
                    <?= $mensagem ?>
                    <?php if ($isMaster): ?>
                        <form method="post" class="mb-3" onsubmit="return confirm('Tem a certeza que pretende apagar TODOS os logs?');">
                            <button type="submit" name="reset_logs" class="btn btn-danger">Reset Total</button>
                        </form>
                    <?php endif; ?>

                    <!-- Barra de pesquisa e botão PDF -->
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <input type="text" id="searchInput" class="form-control w-50" placeholder="Pesquisar na tabela...">
                        <button id="exportPDF" class="btn btn-danger">
                            <i class="fa fa-file-pdf me-2"></i>Exportar PDF
                        </button>
                    </div>

                    <div class="bg-light rounded p-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="logsTable">
                                <thead class="bg-danger text-white">
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilizador</th>
                                        <th>Ação</th>
                                        <th>Detalhes</th>
                                        <th>Data</th>
                                        <?php if ($isMaster): ?><th>Ações</th><?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?= $row['id'] ?></td>
                                        <td><?= htmlspecialchars($row['utilizador'] ?? "Desconhecido") ?></td>
                                        <td><?= htmlspecialchars($row['action']) ?></td>
                                        <td><?= htmlspecialchars($row['details']) ?></td>
                                        <td><?= $row['criado'] ?></td>
                                        <?php if ($isMaster): ?>
                                            <td>
                                                <a href="logs.php?delete=<?= $row['id'] ?>" 
                                                   class="btn btn-sm btn-danger"
                                                   onclick="return confirm('Tem a certeza que pretende apagar este log?');">
                                                    Remover
                                                </a>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
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

<script>
// Pesquisa existente
document.getElementById('searchInput').addEventListener('keyup', function() {
    const searchTerm = this.value.toLowerCase();
    const table = document.getElementById('logsTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let rowContainsSearch = false;

        for (let j = 0; j < cells.length; j++) {
            if (j === cells.length - 1 && <?= $isMaster ? 'true' : 'false'; ?>) continue;

            const cell = cells[j];
            const cellText = cell.textContent;

            if (searchTerm && cellText.toLowerCase().includes(searchTerm)) {
                const regex = new RegExp(`(${searchTerm})`, 'gi');
                cell.innerHTML = cellText.replace(regex, '<mark>$1</mark>');
                rowContainsSearch = true;
            } else {
                cell.innerHTML = cellText;
            }
        }

        rows[i].style.display = rowContainsSearch || !searchTerm ? '' : 'none';
    }
});

// Exportar PDF
document.getElementById('exportPDF').addEventListener('click', function() {
    const table = document.getElementById('logsTable');

    // Clonar tabela para não alterar a original
    const cloneTable = table.cloneNode(true);

    // Remover última coluna se for master (Ações)
    const headers = cloneTable.querySelectorAll('thead tr th');
    if (headers[headers.length - 1].textContent === 'Ações') {
        headers[headers.length - 1].remove();
        const rows = cloneTable.querySelectorAll('tbody tr');
        rows.forEach(row => row.removeChild(row.lastElementChild));
    }

    const tableHTML = cloneTable.outerHTML;

    // Criar form para enviar via POST
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'export_pdf.php';

    // HTML da tabela
    const inputHTML = document.createElement('input');
    inputHTML.type = 'hidden';
    inputHTML.name = 'html';
    inputHTML.value = tableHTML;
    form.appendChild(inputHTML);

    // Nome do PDF
    const inputName = document.createElement('input');
    inputName.type = 'hidden';
    inputName.name = 'filename';
    inputName.value = 'logs_sistema.pdf';
    form.appendChild(inputName);

    // Título do PDF
    const inputTitle = document.createElement('input');
    inputTitle.type = 'hidden';
    inputTitle.name = 'title';
    inputTitle.value = 'Exportação de Logs do Sistema';
    form.appendChild(inputTitle);

    document.body.appendChild(form);
    form.submit();
});
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('open');
});
</script>
</body>
</html>
