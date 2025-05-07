<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitación a colaborar</title>
    <link rel="stylesheet" href="<?php base_url('public/css/mail.css') ?>">
</head>
<body>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    color: #333;
}
.container {
    max-width: 600px;
    margin: 20px auto;
    background-color: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}
.header {
    background-color: #007bff;
    color: #ffffff;
    padding: 20px;
    text-align: center;
}
.content {
    padding: 20px;
    line-height: 1.6;
}
.code {
    font-size: 24px;
    font-weight: bold;
    color: #007bff;
    text-align: center;
    margin: 20px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
}
.button {
    display: inline-block;
    padding: 10px 20px;
    margin: 20px auto;
    background-color: #007bff;
    color: #ffffff !important;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}
.footer {
    background-color: #f4f4f4;
    padding: 10px;
    text-align: center;
    font-size: 12px;
    color: #666;
}
@media only screen and (max-width: 600px) {
    .container {
        width: 100%;
        margin: 10px;
    }
    .content {
        padding: 15px;
    }
}
</style>
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