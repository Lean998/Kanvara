<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-secondary">
  <div class="container-fluid min-vh-100 d-flex justify-content-center align-items-center">
    <div class="row w-100">
      <div class="col-12 col-sm-8 col-md-6 col-lg-4 mx-auto">
        <div class="card shadow-sm">
          <div class="card-body">
            <h2 class="card-title text-center mb-4">Login de Usuarios</h2>
            
            <?php if (session('success')): ?>
              <p class="text-success text-center"><?= session('success') ?></p>
            <?php endif; ?>

            <?php if (session('error')): ?>
              <p class="text-danger text-center"><?= session('error') ?></p>
            <?php endif; ?>

            <form action='user-authenticate' method="post">
              <?= csrf_field() ?>

              <div class="mb-3">
                <label for="userEmail" class="form-label">Email:</label>
                <input type="text" name="email" id="userEmail" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" value="<?= old('email') ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.email') ?? '' ?>
                </div>
              </div>

              <div class="mb-3">
                <label for="userPass" class="form-label">Contraseña:</label>
                <input type="password" name="password" id="userPass" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" required>
                <div class="invalid-feedback">
                  <?= session('errors.password') ?? '' ?>
                </div>
              </div>

              <div class="d-grid mb-2">
                <input type="submit" value="Iniciar Sesión" class="btn btn-primary">
              </div>

              <div class="text-center">
                <a href="<?= base_url('auth/register') ?>" class="btn btn-link">¿No tenés cuenta? Registrate</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>