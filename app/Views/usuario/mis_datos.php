<?php 
use App\Models\UserModel;
if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>

<div class="container mt-5">
  <h3 class="mb-4">Datos del Usuario</h3>
  <form action="<?= base_url('usuarios/actualizar-datos') ?>" id="formActualizarDatos" method="post">
    <div class="card p-4 shadow-sm">
      <div class="mb-3">
        <label for="user_name" class="form-label">Nombre</label>
        <input type="text" name="username" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" id="user_name" value="<?= esc($usuario['user_name']) ?>">
        <div class="invalid-feedback">
          <?= session('errors.username') ?? '' ?>
        </div>
      </div>

      <div class="mb-3">
        <label for="user_email" class="form-label">Email</label>
        <input type="email" name="user_email" class="form-control <?= session('errors.user_email') ? 'is-invalid' : '' ?>" id="user_email" value="<?= esc($usuario['user_email']) ?>">
        <div class="invalid-feedback">
          <?= session('errors.user_email') ?? '' ?>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="text" class="form-control" value="La contraseña no puede mostrarse" readonly>
        <small class="text-muted">Por seguridad, no se muestra la contraseña. Podés cambiarla si lo deseás.</small>
      </div>

      

      <div class="text-end d-flex justify-content-between">
        <span class="text-start">
          <a href="<?= base_url('usuarios/eliminar-usuario') ?>" class="text-center btn btn-danger">Eliminar usuario</a>
        </span>
        <div>
          <span class="text-end">
            <a href="<?= base_url('usuarios/cambiar-password') ?>" class="text-center btn btn-secondary">Cambiar contraseña</a>
          </span>
          <button type="submit" class="btn btn-primary" form="formActualizarDatos">Actualizar datos</button>
        </div>
      </div>
    </div>
  </form>
</div>

<?= $this->endSection() ?>