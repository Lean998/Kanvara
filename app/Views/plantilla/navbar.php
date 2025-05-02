<!-- Navbar -->
<link rel="stylesheet" href="<?php base_url('public/css/navbar.css') ?> ">
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        
    </div>
</nav>

<!-- Offcanvas para el menú hamburguesa -->
<div class="offcanvas offcanvas-start bg-dark text-light" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-light" id="sidebarMenuLabel">Menú</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-light <?= session('opcion') == '' ? 'active' : '' ?>" href="<?= base_url() ?>">Mis Tareas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= session('opcion') == 'tareas/tareas-compartidas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-compartidas') ?>">Compartidas conmigo</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= session('opcion') == 'tareas/tareas-archivadas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-archivadas') ?>">Archivadas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-light <?= session('opcion') == 'tareas/tareas-eliminadas' ? 'active' : '' ?>" href="<?= base_url('tareas/tareas-eliminadas') ?>">Eliminadas</a>
            </li>
        </ul>
    </div>
</div>

<style>

</style>