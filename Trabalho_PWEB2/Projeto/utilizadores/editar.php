    <?php
    session_start();
    include "../DBConnection.php";

    // Só admin pode aceder
    if (!isset($_SESSION["iduser"]) || $_SESSION["perfil"] != "administrador") {
        header("Location: ../signin.php");
        exit;
    }

    if (!isset($_GET['id'])) {
        header("Location: listar.php");
        exit;
    }

    $idUser = intval($_GET['id']);

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

    // Processar formulário
    $mensagem = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome     = $_POST["nome"];
        $email    = $_POST["email"];
        $telefone = $_POST["telefone"];
        $pass     = $_POST["pass"];
        $perfil   = (isset($_POST["perfil"]) && $_SESSION["iduser"] == 0) ? $_POST["perfil"] : $user['perfil'];

        if (!empty($pass)) {
            $hashPass = password_hash($pass, PASSWORD_DEFAULT);
            $sqlUpdate = "UPDATE utilizadores SET nome=?, email=?, telefone=?, password=?, perfil=? WHERE id=?";
            $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "sssssi", $nome, $email, $telefone, $hashPass, $perfil, $idUser);
        } else {
            $sqlUpdate = "UPDATE utilizadores SET nome=?, email=?, telefone=?, perfil=? WHERE id=?";
            $stmtUpdate = mysqli_prepare($link, $sqlUpdate);
            mysqli_stmt_bind_param($stmtUpdate, "ssssi", $nome, $email, $telefone, $perfil, $idUser);
        }

        if (mysqli_stmt_execute($stmtUpdate)) {
            $mensagem = "<div class='alert alert-success'>Utilizador atualizado com sucesso. A redirecionar...</div>";

            // Log
            $acao     = "Edição de utilizador";
            $detalhes = $_SESSION["nome"] . " editou o utilizador $nome";
            $stmt_log = mysqli_prepare($link, "INSERT INTO logs (user_id, action, details) VALUES (?, ?, ?)");
            if ($stmt_log) {
                mysqli_stmt_bind_param($stmt_log, "iss", $_SESSION["iduser"], $acao, $detalhes);
                mysqli_stmt_execute($stmt_log);
                mysqli_stmt_close($stmt_log);
            }

            echo "<script>setTimeout(function(){ window.location.href='listar.php'; }, 2000);</script>";
        } else {
            $mensagem = "<div class='alert alert-danger'>Erro: " . mysqli_error($link) . "</div>";
        }
        mysqli_stmt_close($stmtUpdate);
    }

    $perfil = $_SESSION['perfil'] ?? 'utilizador';
    ?>
    <!DOCTYPE html>
    <html lang="pt">
    <head>
    <meta charset="utf-8">
    <title>Editar Utilizador - Gestor de Tarefas</title>
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
                        <a href="remover.php" class="nav-item nav-link">Eliminar Utilizador</a>
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
                            <?php $userImg = ($perfil == 'administrador') ? "../img/admin.png" : "../img/user.png"; ?>
                            <img class="rounded-circle me-lg-2" src="<?= $userImg ?>" style="width:40px; height:40px;">
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
                            <h3 class="mb-4">Editar Utilizador</h3>
                            <form method="post" id="editarForm">
                                <div class="mb-3">
                                    <label class="form-label">Nome</label>
                                    <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($user['nome']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Telefone</label>
                                    <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($user['telefone']) ?>">
                                </div>

                                <?php if ($_SESSION["iduser"] == 0 && $user['id'] != 0): ?>
                                <div class="mb-3">
                                    <label class="form-label">Perfil</label>
                                    <select name="perfil" class="form-select" required>
                                        <option value="utilizador" <?= ($user['perfil']=='utilizador') ? 'selected':'' ?>>Utilizador</option>
                                        <option value="administrador" <?= ($user['perfil']=='administrador') ? 'selected':'' ?>>Administrador</option>
                                    </select>
                                </div>
                                <?php endif; ?>

                                <div class="mb-3 position-relative d-flex align-items-center">
                                    <div class="flex-grow-1 position-relative">
                                        <label class="form-label" id="passLabel">Nova Palavra-passe (deixe vazio para manter)</label>
                                        <input type="password" name="pass" class="form-control" id="passInput" readonly>
                                        <br><span toggle="#passInput" class="fa fa-fw fa-eye field-icon toggle-password"
                                    style="position:absolute; top:50%; right:15px; transform:translateY(-35%); cursor:pointer; display:none;"></span>
                                    </div>
                                    <button type="button" class="btn btn-warning ms-2 mt-2" id="liberarPass">Liberar</button>
                                </div>

                                <div class="text-end">
                                    <button type="reset" class="btn btn-secondary me-2">Limpar</button>
                                    <button type="submit" class="btn btn-primary">Guardar Alterações</button>
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

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/main.js"></script>
    <script>
        // Liberar password
        document.getElementById('liberarPass').addEventListener('click', function() {
            const input = document.getElementById('passInput');
            input.removeAttribute('readonly');
            input.focus();
            document.getElementById('passLabel').innerText = "Nova Palavra-passe";
            this.style.display = 'none';
            document.querySelector('.toggle-password').style.display = 'block';
        });

        // Alternar visibilidade da password
        document.querySelector('.toggle-password').addEventListener('click', function() {
            const input = document.getElementById('passInput');
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

        // Confirmação antes de submeter
        document.getElementById('editarForm').addEventListener('submit', function(e) {
            if (!confirm("Tem a certeza que pretende alterar este utilizador?")) {
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
