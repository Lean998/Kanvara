
<?php 
  if (!session()->has('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>
<h1>
  Bienvenido a la pagina de inicio
</h1>

<a href="<?= base_url('/auth/log-out') ?>"><button>Cerrar Sesion</button></a>
<?= $this->endSection() ?>