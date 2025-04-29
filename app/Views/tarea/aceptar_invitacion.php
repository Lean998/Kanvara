<?php 
  helper('form');
  if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('botones') ?>
<?= $this->endSection()?>
<?= $this->section('contenido') ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Aceptar Invitación</h2>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= esc(session()->getFlashdata('success')) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?= esc($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($task_id): ?>
                        <p class="text-center">Estás aceptando una invitación para la tarea: <strong><?= esc($task_title) ?></strong></p>
                    <?php endif; ?>

                    <?= form_open(base_url().'tareas/aceptar-invitacion') ?>
                        <div class="mb-3">
                            <label for="invitation_code" class="form-label">Código de Invitación</label>
                            <input type="text" name="invitation_code" id="invitation_code" class="form-control" placeholder="Ingresa el código" required>
                        </div>
                        <?php if ($task_id): ?>
                            <input type="hidden" name="task_id" value="<?= esc($task_id) ?>">
                        <?php endif; ?>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Aceptar Invitación</button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection()?>