<?php 
if (!session('user_id')) {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout') ?>
<?= $this->section('botones') ?>
<?= $this->endSection() ?>
<?= $this->section('contenido') ?>
<div class="container">
    <form action="<?= base_url('subtask/agregar-colaborador') ?>" method="post" id="subtaskEditForm" class="container mt-4 p-3 border rounded shadow-sm bg-light">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="subtaskResponsible" class="form-label">Responsable:</label>
            <?php if (!empty($colab)): ?>
                <select name="subtaskResponsible" id="subtaskResponsible" class="form-select <?= session('errors.subtaskResponsible') ? 'is-invalid' : '' ?>" required>
                    <option value="" disabled>-- Selecciona un colaborador --</option>
                    <?php foreach ($colab as $colaborador): ?>
                        <option value="<?= esc($colaborador['user_id']) ?>" 
                                <?= $subtask['user_id'] == $colaborador['user_id'] ? 'selected' : '' ?>>
                            <?= esc($colaborador['user_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php else: ?>
                <p class="text-muted">No hay colaboradores disponibles para asignar.</p>
                <input type="hidden" name="subtaskResponsible" value="">
            <?php endif; ?>
            <div class="invalid-feedback">
                <?= session('errors.subtaskResponsible') ?? '' ?>
            </div>
        </div>
        <input type="hidden" name="subtask_id" value="<?= esc($subtask['subtask_id']) ?>">
        <input type="hidden" name="task_id" value="<?= esc($subtask['task_id']) ?>">
        
        <div class="text-end">
            <button type="submit" class="btn btn-primary" form="subtaskEditForm">Agregar Colaborador</button>
        </div>

        <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
        <?php endif; ?>
    </form>
</div>
<?= $this->endSection() ?>