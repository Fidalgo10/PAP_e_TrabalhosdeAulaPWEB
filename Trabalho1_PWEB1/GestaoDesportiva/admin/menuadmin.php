<aside id="sidebar-left" class="sidebar-left">
    <div class="sidebar-header">
        <div class="sidebar-title">Navegação</div>
        <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
            <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
        </div>
    </div>

    <div class="nano">
        <div class="nano-content">
            <nav id="menu" class="nav-main" role="navigation">
                <ul class="nav nav-main">

                    <li>
                        <a class="nav-link" href="/php/gestaodesportiva/inicio.php">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <span>Painel de Controlo</span>
                        </a>                        
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <span>Utilizadores</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a class="nav-link" href="/php/gestaodesportiva/utilizadores/inserir.php">Inserir Utilizador</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/utilizadores/listar.php">Listar Utilizadores</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/utilizadores/remover.php">Remover Utilizador</a></li>
                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="fa fa-futbol-o" aria-hidden="true"></i>
                            <span>Modalidades</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a class="nav-link" href="/php/gestaodesportiva/modalidades/inserir.php">Inserir Modalidade</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/modalidades/listar.php">Listar Modalidades</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/modalidades/remover.php">Remover Modalidade</a></li>
                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="fa fa-user" aria-hidden="true"></i>
                            <span>Treinadores</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a class="nav-link" href="/php/gestaodesportiva/treinadores/inserir.php">Associar Modalidade</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/treinadores/listar.php">Listar Treinadores associados</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/treinadores/remover.php">Desassociar Treinador</a></li>
                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="fa fa-child" aria-hidden="true"></i>
                            <span>Atletas</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a class="nav-link" href="/php/gestaodesportiva/atletas/inserir.php">Associar Atleta</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/atletas/listar.php">Listar Atletas associados</a></li>
                            <li><a class="nav-link" href="/php/gestaodesportiva/atletas/remover.php">Desassociar Atleta</a></li>
                        </ul>
                    </li>

                    <li class="nav-parent">
                        <a class="nav-link" href="#">
                            <i class="fa fa-history" aria-hidden="true"></i>
                            <span>Logs</span>
                        </a>
                        <ul class="nav nav-children">
                            <li><a class="nav-link" href="/php/gestaodesportiva/admin/logs.php">Logs do sistema</a></li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </div>
    </div>
</aside>
