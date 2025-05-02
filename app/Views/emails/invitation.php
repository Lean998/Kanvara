<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a colaborar</title>
    <link rel="stylesheet" href="<?php base_url('public/css/mail.css') ?>">
</head>
<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <h1>Invitación a colaborar</h1>
        </div>

        <!-- Contenido -->
        <div class="content">
            <p>Hola,</p>
            <p>Has sido invitado a colaborar en la tarea <strong><?= esc($task_title) ?></strong>.</p>
            <p>Para aceptar la invitación, utiliza el siguiente código:</p>
            <div class="code"><?= esc($code) ?></div>
            <p>Puedes ingresar el código haciendo clic en el botón a continuación:</p>
            <p style="text-align: center;">
                <a href="<?= esc($accept_url) ?>" class="button">Aceptar Invitación</a>
            </p>
            <p>Esta invitación expirará en 5 minutos, así que asegúrate de usarla pronto.</p>
            <p>Si no solicitaste esta invitación, ignora este correo.</p>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Kanvara. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>