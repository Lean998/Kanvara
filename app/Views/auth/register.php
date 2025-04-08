<h2>Registro de Usuarios</h2>

<form action='user-up' method="post">
  <?= csrf_field() ?>
  <label for="userName">Usuario:</label>
  <input type="text" name="username" id="userName" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" value="<?= old('username') ?>" required><br>
  <div class="invalid-feedback">
    <?= session('errors.username') ?? '' ?>
  </div>
  <label for="userEmail">Email:</label>
  <input type="email" name="email" id="userEmail" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required><br>
  <div class="invalid-feedback">
    <?= session('errors.email') ?? '' ?>
  </div>
  <label for="userPass">Contraseña:</label>
  <input type="password" name="password" id="userPass" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" required><br>
  <div class="invalid-feedback">
    <?= session('errors.password') ?? '' ?>
  </div>
  <label for="confirmPass">Confirme su Contraseña:</label>
  <input type="password" name="confirm_password" id="confirmPass" class="form-control <?= session('errors.confirm_password') ? 'is-invalid' : '' ?>" required> <br>
  <div class="invalid-feedback">
    <?= session('errors.confirm_password') ?? '' ?>
  </div>
  <input type="submit" value="Registrarse">
</form>