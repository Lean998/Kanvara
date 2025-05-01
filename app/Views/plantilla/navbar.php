<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<div class="offcanvas offcanvas-start bg-dark text-light" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-light" id="sidebarMenuLabel">Menú</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-light <?= $opcion == '' ? 'active' : '' ?>" href="<?= base_url() ?>">Mis Tareas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= $opcion == 'compartidas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-compartidas') ?>">Compartidas conmigo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= $opcion == 'archivadas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-archivadas') ?>">Archivadas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= $opcion == 'eliminadas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-eliminadas') ?>">Eliminadas</a>
            </li>
        </ul>
    </div>
</div>

<style>
      /* Ajustes para el sidebar y el contenido principal */
@media (max-width: 991.98px) { /* Punto de ruptura lg */
    .main-content {
        margin-left: 0 !important;
    }
    .container-fluid {
        margin-left: 0 !important;
    }
    .container {
        padding: 0.5rem; /* Reducir padding en pantallas pequeñas */
    }
}

/* Estilos del sidebar */
.nav-link.active {
    background-color: #495057;
    border-radius: 5px;
}
.nav-link:hover {
    background-color: #6c757d;
    border-radius: 5px;
}
#sidebar.collapsed {
    width: 60px;
    overflow: hidden;
}
#sidebar.collapsed .sidebar-content {
    display: none;
}
#sidebar.collapsed h4 {
    display: none;
}
.main-content.collapsed {
    margin-left: 60px !important;
}
</style>