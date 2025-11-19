<?php
session_start();
if (!isset($_SESSION['iduser'])) {
    header("Location: signin.php");
    exit();
}

include 'DBConnection.php';

$perfil = $_SESSION['perfil'] ?? 'utilizador';
$userId = $_SESSION['iduser'];

// Estatísticas rápidas
if ($perfil === 'administrador') {
    $totalUsers = $link->query("SELECT COUNT(*) FROM utilizadores")->fetch_row()[0];
    $totalTasks = $link->query("SELECT COUNT(*) FROM tarefas")->fetch_row()[0];
    $tasksPend  = $link->query("SELECT COUNT(*) FROM tarefas WHERE status='pendente'")->fetch_row()[0];
    $tasksProg  = $link->query("SELECT COUNT(*) FROM tarefas WHERE status='em_progresso'")->fetch_row()[0];
    $tasksConc  = $link->query("SELECT COUNT(*) FROM tarefas WHERE status='concluida'")->fetch_row()[0];
    $totalLogs  = $link->query("SELECT COUNT(*) FROM logs")->fetch_row()[0];

    // Últimas 5 tarefas
    $latestTasks = $link->query("
        SELECT t.titulo, u.nome AS assigned_to, t.status 
        FROM tarefas t 
        LEFT JOIN utilizadores u ON t.assigned_to = u.id 
        ORDER BY t.criado DESC LIMIT 5
    ");

    // Últimos 5 logs
    $latestLogs = $link->query("
        SELECT l.id, u.nome AS utilizador, l.action, l.details, l.criado 
        FROM logs l 
        LEFT JOIN utilizadores u ON l.user_id = u.id 
        ORDER BY l.criado DESC LIMIT 5
    ");
} else {
    $totalTasks = $link->query("SELECT COUNT(*) FROM tarefas WHERE assigned_to = $userId")->fetch_row()[0];
    $tasksPend  = $link->query("SELECT COUNT(*) FROM tarefas WHERE assigned_to = $userId AND status='pendente'")->fetch_row()[0];
    $tasksProg  = $link->query("SELECT COUNT(*) FROM tarefas WHERE assigned_to = $userId AND status='em_progresso'")->fetch_row()[0];
    $tasksConc  = $link->query("SELECT COUNT(*) FROM tarefas WHERE assigned_to = $userId AND status='concluida'")->fetch_row()[0];

    // Últimas 5 tarefas do utilizador
    $latestTasks = $link->query("
        SELECT t.titulo, t.data_limite, u.nome AS assigned_to, t.status 
        FROM tarefas t 
        LEFT JOIN utilizadores u ON t.assigned_to = u.id 
        WHERE t.assigned_to = $userId OR t.created_by = $userId
        ORDER BY t.criado DESC LIMIT 5
    ");
}

$today = date('Y-m-d');

// Preparar tarefas em atraso
$overdueAdminTasks = [];
$overdueUserTasks  = [];

if ($perfil === 'administrador') {
    $result = $link->query("
        SELECT t.titulo, t.data_limite, u.nome AS assigned_to, t.status
        FROM tarefas t
        LEFT JOIN utilizadores u ON t.assigned_to = u.id
        WHERE t.data_limite < '$today' AND t.status != 'concluida'
        ORDER BY t.data_limite ASC
    ");
    while ($row = $result->fetch_assoc()) {
        $overdueAdminTasks[] = $row;
    }
} else {
    $result = $link->query("
        SELECT t.titulo, t.data_limite, t.status
        FROM tarefas t
        WHERE (t.assigned_to = $userId OR t.created_by = $userId)
          AND t.data_limite < '$today'
          AND t.status != 'concluida'
        ORDER BY t.data_limite ASC
    ");
    while ($row = $result->fetch_assoc()) {
        $overdueUserTasks[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <title>Gestão de Tarefas</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

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
        .hover-shadow:hover { box-shadow: 0 4px 15px rgba(0,0,0,0.15); transition: all 0.3s; }
        canvas { max-width: 100%; }
        .table-container { max-height: 250px; overflow-y: auto; margin-bottom: 20px; }
        .status-pendente { background-color: #f3d575 !important; color: #695006 !important;  }
        .status-em_progresso { background-color: #5a90e8 !important; color: #0b2e34 !important;}
        .status-concluida { background-color: #68e686 !important; color: #073511 !important;}
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
                <a href="index.php" class="nav-item nav-link active">
                    <i class="fa fa-tachometer-alt me-2"></i>Dashboard
                </a>
                <a href="#tarefasSubmenu" class="nav-item nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" aria-expanded="false">
                    <span><i class="fa fa-check-square me-2"></i>Tarefas</span>
                    <i class="fa fa-chevron-down"></i>
                </a>
                <div class="collapse ps-4" id="tarefasSubmenu">
                    <a href="tarefas/inserir_tarefas.php" class="nav-item nav-link">Inserir Tarefa</a>
                    <a href="tarefas/listar_tarefas.php" class="nav-item nav-link">Listar Tarefas</a>
                    <a href="tarefas/remover_tarefas.php" class="nav-item nav-link">Remover Tarefas</a>
                </div>

                <?php if ($perfil == 'administrador'): ?>
                    <a href="#utilizadoresSubmenu" class="nav-item nav-link d-flex justify-content-between align-items-center" data-bs-toggle="collapse" aria-expanded="false">
                        <span><i class="fa fa-users me-2"></i>Utilizadores</span>
                        <i class="fa fa-chevron-down"></i>
                    </a>
                    <div class="collapse ps-4" id="utilizadoresSubmenu">
                        <a href="utilizadores/inserir.php" class="nav-item nav-link">Inserir Utilizador</a>
                        <a href="utilizadores/listar.php" class="nav-item nav-link">Listar Utilizadores</a>
                        <a href="utilizadores/remover.php" class="nav-item nav-link">Eliminar Utilizador</a>
                    </div>
                    <a href="logs.php" class="nav-item nav-link">
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
    <div class="content">
        <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
            <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                <h2 class="text-<?= $perfil==='administrador' ? 'danger' : 'primary' ?> mb-0"><i class="fa fa-tasks"></i></h2>
            </a>
            <div class="navbar-nav align-items-center ms-auto">
                <div class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                        <?php $userImg = ($perfil === 'administrador') ? "img/admin.png" : "img/user.png"; ?>
                        <img class="rounded-circle me-lg-2" src="<?= $userImg ?>" style="width: 40px; height: 40px;">
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
        <div class="container-fluid pt-4 px-4">
            <div class="d-flex justify-content-end mb-3">
            <div class="d-flex justify-content-end mb-3">
                <button id="exportDashboardPDF" class="btn btn-<?= $perfil==='administrador'?'danger':'primary' ?>">
                    <i class="fa fa-file-pdf me-2"></i>Exportar Dashboard PDF
                </button>
            </div>
        </div>
        <div id="dashboardContent">
            <!-- Cards -->
            <div class="row g-4 mb-4">
                <?php if($perfil === 'administrador'): ?>
                    <div class="col-sm-6 col-xl-4">
                        <a href="utilizadores/listar.php" class="text-decoration-none text-danger">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                                <i class="fa fa-users fa-3x text-danger"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Utilizadores</p>
                                    <h6 class="mb-0"><?=$totalUsers?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <a href="logs.php" class="text-decoration-none text-danger">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                                <i class="fa fa-history fa-3x text-danger"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Logs</p>
                                    <h6 class="mb-0"><?=$totalLogs?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6 col-xl-4">
                        <a href="tarefas/listar_tarefas.php" class="text-decoration-none text-danger">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                                <i class="fa fa-tasks fa-3x text-danger"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Tarefas Totais</p>
                                    <h6 class="mb-0"><?=$totalTasks?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="col-sm-6 col-xl-3">
                        <a href="tarefas/listar_tarefas.php" class="text-decoration-none text-primary">
                            <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                                <i class="fa fa-tasks fa-3x text-primary"></i>
                                <div class="ms-3">
                                    <p class="mb-2">Tarefas Totais</p>
                                    <h6 class="mb-0"><?=$totalTasks?></h6>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Pendentes -->
                <div class="<?= $perfil === 'administrador' ? 'col-sm-6 col-xl-4' : 'col-sm-6 col-xl-3' ?>">
                    <a href="tarefas/listar_tarefas.php?status=pendente" class="text-decoration-none text-warning">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                            <i class="fa fa-hourglass-half fa-3x text-warning"></i>
                            <div class="ms-3">
                                <p class="mb-2">Pendentes</p>
                                <h6 class="mb-0"><?=$tasksPend?></h6>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Em Progresso -->
                <div class="<?= $perfil === 'administrador' ? 'col-sm-6 col-xl-4' : 'col-sm-6 col-xl-3' ?>">
                    <a href="tarefas/listar_tarefas.php?status=em_progresso" class="text-decoration-none text-info">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                            <i class="fa fa-spinner fa-3x text-info"></i>
                            <div class="ms-3">
                                <p class="mb-2">Em Progresso</p>
                                <h6 class="mb-0"><?=$tasksProg?></h6>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Concluídas -->
                <div class="<?= $perfil === 'administrador' ? 'col-sm-6 col-xl-4' : 'col-sm-6 col-xl-3' ?>">
                    <a href="tarefas/listar_tarefas.php?status=concluida" class="text-decoration-none text-success">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4 hover-shadow">
                            <i class="fa fa-check fa-3x text-success"></i>
                            <div class="ms-3">
                                <p class="mb-2">Concluídas</p>
                                <h6 class="mb-0"><?=$tasksConc?></h6>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Gráfico + Tabelas -->
            <div class="row g-4 mb-4">
                <div class="col-lg-6">
                    <div class="bg-light rounded p-4">
                        <h5>Tarefas por Status</h5>
                        <canvas id="tasksChart" style="height:250px;"></canvas>
                    </div>
                </div>

                <div class="col-lg-6">
                    <!-- Últimas Tarefas -->
                    <div class="bg-light rounded p-4 table-container">
                        <h5>Últimas Tarefas</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <?php if($perfil === 'administrador'): ?>
                                        <th>Responsável</th>
                                    <?php else: ?>
                                        <th>Data Limite</th>
                                    <?php endif; ?>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($task = $latestTasks->fetch_assoc()): ?>
                                    <tr class="status-<?= strtolower(str_replace(' ', '_', $task['status'])) ?>">
                                        <td><?=htmlspecialchars($task['titulo'])?></td>
                                        <?php if($perfil === 'administrador'): ?>
                                            <td><?=htmlspecialchars($task['assigned_to'] ?? 'Desconhecido')?></td>
                                        <?php else: ?>
                                            <td><?=htmlspecialchars($task['data_limite'])?></td>
                                        <?php endif; ?>
                                        <td><?=htmlspecialchars($task['status'])?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($perfil === 'administrador'): ?>
                        <!-- Últimos Logs -->
                        <div class="bg-light rounded p-4 table-container">
                            <h5>Últimos Logs</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Utilizador</th>
                                        <th>Ação</th>
                                        <th>Detalhes</th>
                                        <th>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($log = $latestLogs->fetch_assoc()): ?>
                                        <tr>
                                            <td><?=$log['id']?></td>
                                            <td><?=htmlspecialchars($log['utilizador'] ?? 'Desconhecido')?></td>
                                            <td><?=htmlspecialchars($log['action'])?></td>
                                            <td><?=htmlspecialchars($log['details'])?></td>
                                            <td><?=$log['criado']?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <!-- Tarefas em atraso para utilizador -->
                        <div class="bg-light rounded p-4 table-container">
                            <h5>Tarefas em atraso</h5>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Data Limite</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($overdueUserTasks) > 0): ?>
                                        <?php foreach($overdueUserTasks as $task): ?>
                                            <tr class="status-<?= strtolower(str_replace(' ', '_', $task['status'])) ?>">
                                                <td><?=htmlspecialchars($task['titulo'])?></td>
                                                <td><?=htmlspecialchars($task['data_limite'])?></td>
                                                <td><?=htmlspecialchars($task['status'])?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center">Nenhuma tarefa em atraso</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if($perfil === 'administrador'): ?>
                <!-- Tarefas em atraso abaixo para admin -->
                <div class="bg-light rounded p-4 table-container mt-4">
                    <h5>Tarefas em atraso</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Título</th>
                                <th>Responsável</th>
                                <th>Data Limite</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($overdueAdminTasks) > 0): ?>
                                <?php foreach($overdueAdminTasks as $task): ?>
                                    <tr class="status-<?= strtolower(str_replace(' ', '_', $task['status'])) ?>">
                                        <td><?=htmlspecialchars($task['titulo'])?></td>
                                        <td><?=htmlspecialchars($task['assigned_to'] ?? 'Desconhecido')?></td>
                                        <td><?=htmlspecialchars($task['data_limite'])?></td>
                                        <td><?=htmlspecialchars($task['status'])?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">Nenhuma tarefa em atraso</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
            <!-- Footer -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-3 text-center">
                    <small>Gestor de Tarefas — Projeto Programação Web II</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
const ctx = document.getElementById('tasksChart').getContext('2d');
const total = <?=$tasksPend + $tasksProg + $tasksConc?>;
const tasksChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Pendentes', 'Em Progresso', 'Concluídas'],
        datasets: [{
            label: 'Tarefas',
            data: [<?=$tasksPend?>, <?=$tasksProg?>, <?=$tasksConc?>],
            backgroundColor: ['#FFC107', '#17A2B8', '#28A745']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            datalabels: {
                color: '#fff',
                formatter: (value) => total ? Math.round(value/total*100) + '%' : '0%',
                font: { weight: 'bold', size: 14 }
            }
        }
    },
    plugins: [ChartDataLabels]
});
// 📄 Exportar dashboard para PDF
document.getElementById('exportDashboardPDF').addEventListener('click', () => {
    const dashboard = document.getElementById('dashboardContent');

    // Usar html2canvas para capturar a dashboard
    html2canvas(dashboard, { scale: 2 }).then(canvas => {
        const imgData = canvas.toDataURL('image/png');
        const { jsPDF } = window.jspdf;
        const pdf = new jsPDF('p', 'mm', 'a4');

        // Tamanho da página A4 em mm
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvas.height * pdfWidth) / canvas.width;

        pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);

        pdf.save('dashboard.pdf');
    }).catch(err => {
        console.error('Erro ao gerar PDF:', err);
        alert('Não foi possível gerar o PDF. Veja o console.');
    });
});
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.sidebar').classList.toggle('open');
    document.querySelector('.content').classList.toggle('open');
});
</script>
</body>
</html>
