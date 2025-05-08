<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro de Usuarios</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
    <div class="row w-100">
      <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Registro de Usuarios</h2>

            <?php if (session('success')): ?>
              <p class="text-success text-center"><?= session('success') ?></p>
            <?php endif; ?>

            <?php if (session('error')): ?>
              <p class="text-danger text-center"><?= session('error') ?></p>
            <?php endif; ?>

            <form action='user-up' method="post">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label for="userName" class="form-label">Usuario:</label>
                <input type="text" name="usuario" id="userName" class="form-control <?= session('errors.usuario') ? 'is-invalid' : '' ?>" value="<?= old('usuario') ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.usuario') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="userEmail" class="form-label">Email:</label>
                <input type="email" name="email" id="userEmail" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.email') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="userPass" class="form-label">Contraseña:</label>
                <input type="password" name="contraseña" id="userPass" class="form-control <?= session('errors.contraseña') ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.contraseña') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="confirmPass" class="form-label">Confirme su Contraseña:</label>
                <input type="password" name="confirmar_contraseña" id="confirmPass" class="form-control <?= session('errors.confirmar_contraseña') ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.confirmar_contraseña') ?? '' ?>
                </div>
              </div>

              <div class="d-grid mb-2">
                <input type="submit" value="Registrarse" class="btn btn-primary">
              </div>

              <div class="text-center">
                <a href="<?= base_url('auth/login') ?>" class="btn btn-link">¿Ya tenés cuenta? Inicia sesión</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
