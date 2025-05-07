<?php 
use App\Models\UserModel;
if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>

<div class="container mt-5">
  <h3 class="mb-4">Cambiar contraseña</h3>
  <form action=" <?= base_url('usuarios/cambiar-password') ?>" method="post">
    <div class="card p-4 shadow-sm">
        <div class="mb-3">
          <label for="user_password" class="form-label">Contraseña Actual</label>
          <div class="input-group">
            <input type="password" name="user_password" class="password-input form-control <?= session('errors.user_password') ? 'is-invalid' : '' ?>" id="user_password" value="<?= old('user_password') ?>">
            <button class="btn btn-outline-secondary togglePassword" type="button">
              <i class="bi bi-eye"></i>
            </button>
            <div class="invalid-feedback">
              <?= session('errors.user_password') ?? '' ?>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="user_new_password" class="form-label">Nueva Contraseña</label>
          <div class="input-group">
            <input type="password" name="new_password" class="password-input form-control <?= session('errors.new_password') ? 'is-invalid' : '' ?>" id="new_password" value="<?= old('new_password') ?>">
            <button class="btn btn-outline-secondary togglePassword" type="button">
              <i class="bi bi-eye"></i>
            </button>
            <div class="invalid-feedback">
              <?= session('errors.new_password') ?? '' ?>
            </div>
          </div>
        </div>

        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirme la nueva contraseña</label>
          <div class="input-group">
            <input type="password" name="confirm_password" class="password-input form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" id="confirm_password" value="<?= old('confirm_password') ?>">
            <button class="btn btn-outline-secondary togglePassword" type="button">
              <i class="bi bi-eye"></i>
            </button>
            <div class="invalid-feedback">
              <?= session('errors.confirm_password') ?? '' ?>
            </div>
          </div>
        </div>
        

        <div class="text-end">
          <button type="submit" class="btn btn-info">Cambiar contraseña</button>
        </div>
      </div>
    </form>
</div>

<script>
  document.querySelectorAll('.togglePassword').forEach(button => {
    button.addEventListener('click', function () {
      const input = this.previousElementSibling;
      const icon = this.querySelector('i');
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';
      icon.classList.toggle('bi-eye');
      icon.classList.toggle('bi-eye-slash');
    });
  });
</script>
<?= $this->endSection() ?>