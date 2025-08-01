<?php 
  if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('botones') ?>
<?= $this->endSection()?>
<?= $this->section('contenido') ?>
<div class="container">
  <form action="<?= base_url()?>subtask/editar-subtarea"  method="post" id="subtaskEditForm" class="container mt-4 p-3 border rounded shadow-sm bg-light">
    <?= csrf_field() ?>
    <?php if($subtask['user_id'] !== session('user_id')) : ?>
      <div class="mb-3">
        <label for="subtaskDesc" class="form-label">Descripcion:</label>
        <input type="text" name="subtaskDesc" id="subtaskDesc" class="form-control <?= session('errors.subtaskDesc') ? 'is-invalid' : '' ?>" value="<?= $subtask['subtask_desc'] ?>" required><br>
        <div class="invalid-feedback">
          <?= str_replace("subtaskDesc","descripcion",session('errors.subtaskDesc')) ?? '' ?>
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-md-6">
          <label for="subtaskState" class="form-label <?= session('errors.subtaskState') ? 'is-invalid' : '' ?>">Estado:</label>
          <select name="subtaskState" id="subtaskState" class="form-select" required>
            <option value="Definida" <?= $subtask['subtask_state'] === 'Definida' ? 'selected' : '' ?>>Definida</option>
            <option value="En proceso" <?= $subtask['subtask_state'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
            <option value="Completada" <?= $subtask['subtask_state'] === 'Completada' ? 'selected' : '' ?>>Completada</option>
          </select>
          <div class="invalid-feedback">
            <?= str_replace("subtaskState","estado",session('errors.subtaskState')) ?? '' ?>
          </div>
        </div>
            
        <div class="col-md-6">
          <label for="taskPriority" class="form-label">Prioridad:</label>
            <select name="subtaskPriority" id="subtaskPriority" class="form-select <?= session('errors.subtaskPriority') ? 'is-invalid' : '' ?>">
              <option value="Baja" <?=  $subtask['subtask_priority'] === 'baja' ? 'selected' : '' ?>>Baja</option>
              <option value="Normal" <?=  $subtask['subtask_priority'] === 'normal' ? 'selected' : '' ?>>Normal</option>
              <option value="Alta" <?=  $subtask['subtask_priority'] === 'alta' ? 'selected' : '' ?>>Alta</option>
            </select>
            <div class="invalid-feedback">
              <?= str_replace("taskPriority","prioridad",session('errors.taskPriority')) ?? '' ?>
            </div>
          </div>
        </div>

        <div>
          <label for="subtaskExpiry" class="form-label">Fecha limite:</label>
          <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="subtaskExpiry" id="subtaskExpiry" class="form-control <?= session('errors.subtaskExpiry') ? 'is-invalid' : '' ?>" value="<?= $subtask['subtask_expiry'] ?>"><br>
          <div class="invalid-feedback">
            <?= str_replace("subtaskExpiry","fecha limite",session('errors.subtaskExpiry')) ?? '' ?>
          </div>
        </div>


        <div class="mb-3">
          <label for="subtaskResponsible" class="form-label">Responsable:</label>
          <?php if (!empty($colab)): ?>
            <select name="subtaskResponsible" id="subtaskResponsible" class="form-select <?= session('errors.subtaskResponsible') ? 'is-invalid' : '' ?>">
              
              <option value="" <?= empty($subtask['user_id']) ? 'selected' : '' ?>>Sin responsable</option>

              <?php foreach ($colab as $colaborador): ?>
                <option value="<?= $colaborador['user_id'] ?>" <?= ($subtask['user_id'] ?? '') == $colaborador['user_id'] ? 'selected' : '' ?>>
                  <?= $colaborador['user_name'] ?>
                </option>
              <?php endforeach; ?>
            </select>
          <?php endif; ?>
          <div class="invalid-feedback">
            <?= session('errors.subtaskResponsible') ?? '' ?>
          </div>
        </div>
          
        <input type="text" class="d-none" name="subtask_id" value="<?= $subtask['subtask_id'] ?>">
        <input type="text" class="d-none" name="task_id" value="<?= $subtask['task_id'] ?>">
        
        <div class="text-end">
          <button type="submit" class="btn btn-primary" form="subtaskEditForm">Editar Subtarea</button>
        </div>
        <?php else:?>
        <div class="row mb-3">
          <div class="col-12">
            <label for="subtaskState" class="form-label <?= session('errors.subtaskState') ? 'is-invalid' : '' ?>">Estado:</label>
            <select name="subtaskState" id="subtaskState" class="form-select">
              <option value="En proceso" <?= $subtask['subtask_state'] === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
              <option value="Completada" <?= $subtask['subtask_state'] === 'Completada' ? 'selected' : '' ?>>Completada</option>
            </select>
            <div class="invalid-feedback">
              <?= str_replace("subtaskState","estado",session('errors.subtaskState')) ?? '' ?>
            </div>
          </div>
            
        </div>
        <?php endif;?>        
        <?php if ($msg = session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= $msg ?></div>
        <?php endif; ?>
  </form>
</div>
<?= $this->endSection()?>