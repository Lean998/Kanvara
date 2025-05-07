<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.12.1/font/bootstrap-icons.min.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="d-flex flex-column min-vh-100 bg-secondary">
    <!-- Navbar -->
    <?= $this->include('plantilla/navbar') ?>

    <!-- Contenedor para mensajes -->
    <div class="container py-3">
        <div id="mensaje-success" class="alert d-none" role="alert"></div>
    </div>

    <!-- Sección para botones y contenido dinámico -->
    <main class="flex-grow-1">
        <div class="container py-3">
            <?php echo $this->renderSection('botones'); ?>
            <?php echo $this->renderSection('contenido'); ?>
        </div>
    </main>

    <!-- Footer -->
    <?= $this->include('plantilla/footer') ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?= base_url('public/scripts/globales.js') ?>"></script>
    <script>
        const BASE_URL = '<?= base_url() ?>';
    </script>
    <script>
        <?php if (session('success')): ?>
            mostrarMensaje('mensaje-success', <?= json_encode(session('success')) ?>, 'success');
        <?php elseif(session('error')): ?>
            mostrarMensaje('mensaje-success', <?= json_encode(session('error')) ?>, 'danger');
        <?php endif ?>
    </script>
</body>
</html>