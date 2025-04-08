<head><title>Login</title></head>
<body>
<h2>Login de Usuarios</h2>
<?php if (session('success')): ?>
  <p style="color: green;"><?= session('error') ?></p>
<?php endif; ?>
<form action='user-authenticate' method="post">
  <?= csrf_field() ?>
  <label for="userName">Email:</label>
  <input type="text" name="email" id="userEmail" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required><br>
  <div class="invalid-feedback">
    <?= session('errors.email') ?? '' ?>
  </div>
  
  <label for="userPass">Contraseña:</label>
  <input type="password" name="password" id="userPass" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" required><br>
  <div class="invalid-feedback">
    <?= session('errors.password') ?? '' ?>
  </div>
  
  <input type="submit" value="iniciar Sesion">

  <?php if (session('error')): ?>
  <p style="color: red;"><?= session('error') ?></p>
  <?php endif; ?>
</form>
</body>
