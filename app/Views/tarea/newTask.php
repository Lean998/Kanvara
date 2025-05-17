<?php 
$sesion = session();
    if (!$sesion->get("user_id")) {
        return view("auth/login");
    }
?>
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newTask">
  Nueva Tarea
</button>

<!-- Modal -->
<div class="modal fade" id="newTask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newTaskLabel">Nueva tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action='task-create' id="taskCreateForm" method="post">
          <?= csrf_field() ?>

          <label for="taskTitle">Titulo:</label>
          <input type="text" name="taskTitle" id="taskTitle" class="form-control <?= session('errors.taskTitle') ? 'is-invalid' : '' ?>" value="<?= old('taskTitle') ?>" required><br>
          <div class="invalid-feedback">
            <?= str_replace("taskTitle","titulo",session('errors.taskTitle')) ?? '' ?>
          </div>

          <label for="taskDesc">Descripcion:</label>
          <input type="text" name="taskDesc" id="taskDesc" class="form-control <?= session('errors.taskDesc') ? 'is-invalid' : '' ?>" value="<?= old('taskDesc') ?>" required><br>
          <div class="invalid-feedback">
            <?= str_replace("taskDesc","descripcion",session('errors.taskDesc')) ?? '' ?>
          </div>
          
          <label for="taskPriority">Prioridad:</label>
          <select name="taskPriority" id="taskPriority">
            <option value="baja">Baja</option>
            <option value="normal" default>Normal</option>
            <option value="alta">Alta</option>
          </select>
          <div class="invalid-feedback">
            <?= str_replace("taskPriority","prioridad",session('errors.taskPriority')) ?? '' ?>
          </div>
          
          <label for="taskExpiry">Fecha limite:</label>
          <input type="datetime" min="<?= date('Y-m-d') ?>" name="taskExpiry" id="taskExpiry" class="form-control <?= session('errors.taskExpiry') ? 'is-invalid' : '' ?>" value="<?= old('taskExpiry') ?>" required><br>
          <div class="invalid-feedback">
            <?= str_replace("taskExpiry","fecha limite",session('errors.taskExpiry')) ?? '' ?>
          </div>

          <div class="row justify-content-center">
            <div class="form-group col-12 col-md-10 d-flex align-items-center mb-3">
              <input type="checkbox" class="form-check-input bg-input" id="taskReminder" name="taskReminder" value="<?= old('taskReminder') ?>">
              <label class="form-check-label mx-2" for="taskReminder">Agregar recordatorio</label>
            </div>
          </div>

          <div class="additional-fields row justify-content-center">
            <div class="form-group col-12 col-md-5 mb-3">
              <label for="taskReminderDate">Fecha del recordatorio:</label>
              <input type="datetime" min="<?= date('Y-m-d') ?>" name="taskReminderDate" id="taskReminderDate" class="form-control <?= session('errors.taskReminderDate') ? 'is-invalid' : '' ?>" value="<?= old('taskReminderDate') ?>" required><br>
              <div class="invalid-feedback">
                <?= str_replace("taskReminderDate","recordatorio",session('errors.taskReminderDate')) ?? '' ?>
              </div>
            </div>
          </div>

          <label for="taskColor">Color de la Tarea:</label>
          <input type="color" name="taskColor" id="taskColor" class="form-control <?= session('errors.taskColor') ? 'is-invalid' : '' ?>" value="<?= old('taskColor') ?>" required><br>
          <div class="invalid-feedback">
            <?= str_replace("taskColor","color",session('errors.taskColor')) ?? '' ?>
          </div>

          <?php if (session('error')): ?>
            <p style="color: red;"><?= session('error') ?></p>
          <?php endif; ?>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" form="taskCreateForm">Crear Tarea</button>
      </div>
    </div>
  </div>
</div>