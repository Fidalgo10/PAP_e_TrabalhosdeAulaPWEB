<?php
session_start();
include "../DBConnection.php";

if (!isset($_SESSION["iduser"])) {
    header("Location: ../signin.php");
    exit;
}

$perfil = $_SESSION['perfil'] ?? 'utilizador';
$user_id = $_SESSION['iduser'];

// Buscar tarefas
if ($perfil == 'administrador') {
    $sql = "SELECT t.id, t.titulo, t.descricao, t.status, t.data_limite, u.nome AS assigned_to, c.nome AS created_by, t.criado, t.atualizado
            FROM tarefas t
            LEFT JOIN utilizadores u ON t.assigned_to = u.id
            LEFT JOIN utilizadores c ON t.created_by = c.id
            ORDER BY t.id DESC"; // Última criada primeiro
} else {
    $sql = "SELECT t.id, t.titulo, t.descricao, t.status, t.data_limite, u.nome AS assigned_to, c.nome AS created_by, t.criado, t.atualizado
            FROM tarefas t
            LEFT JOIN utilizadores u ON t.assigned_to = u.id
            LEFT JOIN utilizadores c ON t.created_by = c.id
            WHERE t.assigned_to = ? OR t.created_by = ?
            ORDER BY t.id DESC"; // Última criada primeiro
}

$stmt = mysqli_prepare($link, $sql);
if ($perfil == 'utilizador') {
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="pt">
<head>
<meta charset="utf-8">
<title>Listar Tarefas - Gestor de Tarefas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="../img/Instituto_Superior_de_Engenharia_de_Coimbra_logo.png" rel="icon">
<link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="../css/bootstrap.min.css" rel="stylesheet">
<link href="../css/style.css" rel="stylesheet">

<?php
$primaryColor = ($perfil === 'administrador') ? '#b91010' : '#009CFF';
?>
<style>
:root { --primary: <?= $primaryColor ?>; --light: #F3F6F9; --dark: #191C24; }

.sort-arrow { margin-left: 5px; font-size: 0.8em; }

/* Efeito hover nos th clicáveis */
#tarefasTable th[data-type] {
    cursor: pointer; /* muda o cursor para mão */
    transition: all 0.2s ease; /* suaviza o efeito */
}

#tarefasTable th[data-type]:hover {
    transform: scale(1.05); /* aumenta um pouco */
    color: black;   /* muda a cor da letra para dar mais destaque */
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
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="../index.php" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-<?= $perfil==='administrador' ? 'danger' : 'primary' ?> mb-0"><i class="fa fa-tasks"></i></h2>
            </a>
            <div class="navbar-nav align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <?php $userImg = ($perfil == 'administrador') ? "../img/admin.png" : "../img/user.png"; ?>
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
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

        <div class="container-fluid pt-4 px-4">
            <div class="row">
                <div class="col-12">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Lista de Tarefas</h3>
                        <a href="inserir_tarefas.php" class="btn <?= ($perfil == 'administrador') ? 'btn-danger' : 'btn-primary' ?>">
                            <i class="fa fa-plus me-2"></i> Criar Nova Tarefa
                        </a>
                    </div>

                    <!-- Barra de pesquisa e botão PDF -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <input type="text" id="searchInput" class="form-control w-50" placeholder="Pesquisar...">
                        <button id="exportPDF" class="btn <?= ($perfil == 'administrador') ? 'btn-danger' : 'btn-primary' ?>">
                            <i class="fa fa-file-pdf me-2"></i>Exportar PDF
                        </button>
                    </div>

                    <div class="bg-light rounded p-4">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="tarefasTable">
                                <thead class="<?= ($perfil == 'administrador') ? 'bg-danger' : 'bg-primary' ?> text-white">
                                    <tr>
                                        <th data-type="string">Título <span class="sort-arrow"></span></th>
                                        <th data-type="string">Status <span class="sort-arrow"></span></th>
                                        <th data-type="date">Data Limite <span class="sort-arrow"></span></th>
                                        <?php if ($perfil === 'administrador'): ?>
                                            <th data-type="string">Atribuído a <span class="sort-arrow"></span></th>
                                        <?php endif; ?>
                                        <th data-type="string">Criado por <span class="sort-arrow"></span></th>
                                        <th data-type="date">Criado <span class="sort-arrow"></span></th>
                                        <th data-type="date">Atualizado <span class="sort-arrow"></span></th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = mysqli_fetch_assoc($result)) { ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['titulo']) ?></td>
                                        <td><?= htmlspecialchars($row['status']) ?></td>
                                        <td><?= htmlspecialchars($row['data_limite']) ?></td>
                                        <?php if ($perfil === 'administrador'): ?>
                                            <td><?= htmlspecialchars($row['assigned_to']) ?></td>
                                        <?php endif; ?>
                                        <td><?= htmlspecialchars($row['created_by']) ?></td>
                                        <td><?= $row['criado'] ?></td>
                                        <td><?= $row['atualizado'] ?></td>
                                        <td>
                                            <a href="detalhes.php?id=<?= $row['id'] ?>" class="btn btn-sm <?= ($perfil == 'administrador') ? 'btn-danger' : 'btn-primary' ?>">
                                                <i class="fa fa-info-circle"></i> Detalhes
                                            </a>
                                        </td>
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
// 🔍 Pesquisa instantânea
document.getElementById('searchInput').addEventListener('keyup', function() {
    const term = this.value.toLowerCase();
    const rows = document.querySelectorAll('#tarefasTable tbody tr');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(term) ? '' : 'none';
    });
});

// 📄 Exportar PDF
document.getElementById('exportPDF').addEventListener('click', function() {
    const table = document.getElementById('tarefasTable');

    const cloneTable = table.cloneNode(true);
    const headers = cloneTable.querySelectorAll('thead tr th');
    headers[headers.length - 1].remove(); // remove "Ações"
    const rows = cloneTable.querySelectorAll('tbody tr');
    rows.forEach(row => row.removeChild(row.lastElementChild));

    const tableHTML = cloneTable.outerHTML;

    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../export_pdf.php';

    const inputHTML = document.createElement('input');
    inputHTML.type = 'hidden';
    inputHTML.name = 'html';
    inputHTML.value = tableHTML;
    form.appendChild(inputHTML);

    const inputName = document.createElement('input');
    inputName.type = 'hidden';
    inputName.name = 'filename';
    inputName.value = 'lista_de_tarefas.pdf';
    form.appendChild(inputName);

    const inputTitle = document.createElement('input');
    inputTitle.type = 'hidden';
    inputTitle.name = 'title';
    inputTitle.value = 'Exportação da Tabela de Tarefas';
    form.appendChild(inputTitle);

    document.body.appendChild(form);
    form.submit();
});

// 🔀 Ordenação clicável
document.querySelectorAll('#tarefasTable th[data-type]').forEach((header, index) => {
    let asc = true;
    header.addEventListener('click', () => {
        const table = header.closest('table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        const type = header.dataset.type;
        rows.sort((a, b) => {
            let aText = a.children[index].innerText.trim();
            let bText = b.children[index].innerText.trim();

            if(type === 'date'){
                aText = new Date(aText);
                bText = new Date(bText);
            } else {
                aText = aText.toLowerCase();
                bText = bText.toLowerCase();
            }

            return asc ? (aText > bText ? 1 : -1) : (aText < bText ? 1 : -1);
        });

        tbody.innerHTML = '';
        rows.forEach(row => tbody.appendChild(row));

        document.querySelectorAll('.sort-arrow').forEach(s => s.textContent='');
        header.querySelector('.sort-arrow').textContent = asc ? '▲' : '▼';

        asc = !asc;
    });
});
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('open');
});
</script>
</body>
</html>
