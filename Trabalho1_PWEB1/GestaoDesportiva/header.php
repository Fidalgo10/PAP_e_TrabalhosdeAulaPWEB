<header class="header">
    <div class="logo-container">
        <a href="/php/gestaodesportiva/inicio.php" class="logo">
            <img src="/php/gestaodesportiva/img/gestaologo.png" width="80" height="35" alt="Porto Admin" />
        </a>
        <div class="d-md-none toggle-sidebar-left"
             data-toggle-class="sidebar-left-opened"
             data-target="html"
             data-fire-event="sidebar-left-opened">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <!-- start: search & user box -->
    <div class="header-right">
        <div id="userbox" class="userbox" style="top: 7px;">
            <a href="#" data-toggle="dropdown">
                <figure class="profile-picture">
                <?php
                    if ($_SESSION["perfil"] == 'admin') {
                        $imagem_perfil = '/php/gestaodesportiva/img/admin.png';
                    } elseif ($_SESSION["perfil"] == 'treinador') {
                        $imagem_perfil = '/php/gestaodesportiva/img/treinador.png';
                    } else {
                        $imagem_perfil = '/php/gestaodesportiva/img/user.png';
                    }
                ?>
                    <img src="<?php echo $imagem_perfil; ?>" alt="User" class="rounded-circle" data-lock-picture="<?php echo $imagem_perfil; ?>" />
                </figure>
                <div class="profile-info">
                    <span class="name">Olá, <?php echo $_SESSION['nome']; ?>!</span>
                    <span class="role">
                        <?php
                        $perfil = $_SESSION["perfil"];

                        if ($_SESSION["perfil"] == 'admin') {
                            echo "Administrador";
                        } elseif ($perfil == 'treinador') {
                            echo "Treinador";
                        }else {
                            echo "Atleta";
                        }
                        ?>
                    </span>
                </div>
                <i class="fa custom-caret"></i>
            </a>

            <div class="dropdown-menu">
                <ul class="list-unstyled mb-2">
                    <li class="divider"></li>
                    <li>
                        <a tabindex="-1" href="/php/gestaodesportiva/alterardados.php"><i class="fa fa-lock"></i> Editar Dados Pessoais</a>
                    </li>
                    <li>
                        <a role="menuitem" tabindex="-1" href="/php/gestaodesportiva/logout.php"><i class="fa fa-power-off"></i> Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end: search & user box -->
</header>
