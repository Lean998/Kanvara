<?php 
  if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('botones') ?>



<div class="container">
  <div id="mensaje-success" class="alert d-none" role="alert"></div>         
</div>
<!-- Modal -->
<div class="modal fade" id="newTask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newTaskLabel">Nueva Tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action='tareas/crear-tarea' id="taskCreateForm" method="post" class="container mt-4 p-3 border rounded shadow-sm bg-light">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label for="taskTitle" class="form-label">Titulo:</label>
            <input type="text" name="taskTitle" id="taskTitle" class="form-control <?= session('errors.taskTitle') ? 'is-invalid' : '' ?>" value="<?= old('taskTitle') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.taskTitle') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="taskDesc" class="form-label">Descripcion:</label>
            <input type="text" name="taskDesc" id="taskDesc" class="form-control <?= session('errors.taskDesc') ? 'is-invalid' : '' ?>" value="<?= old('taskDesc') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.taskDesc') ?? '' ?>
            </div>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="taskPriority" class="form-label">Prioridad:</label>
              <select name="taskPriority" id="taskPriority" class="form-select">
                <option value="baja" <?= old('taskPriority') === 'baja' ? 'selected' : '' ?>>Baja</option>
                <option value="normal" <?= old('taskPriority') === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="alta" <?= old('taskPriority') === 'alta' ? 'selected' : '' ?>>Alta</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.taskPriority') ?? '' ?>
              </div>
            </div>
            
            <div class="col-md-6">
              <label for="taskExpiry" class="form-label">Fecha limite:</label>
              <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="taskExpiry" id="taskExpiry" class="form-control <?= session('errors.taskExpiry') ? 'is-invalid' : '' ?>" value="<?= old('taskExpiry') ?>" required><br>
              <div class="invalid-feedback">
                <?= session('errors.taskExpiry') ?? '' ?>
              </div>
            </div>
            
          </div>
          

          <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input bg-input" id="taskReminder" name="taskReminder" value="<?= old('taskReminder') ?>">
                <label class="form-check-label mx-2" for="taskReminder">Agregar recordatorio</label>
          </div>

          <div class="row mb-3 additional-fields" style="display: none;">
              <div class="col-md-6">
                <label for="taskReminderDate" class="form-label">Fecha del recordatorio:</label>
                <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="taskReminderDate" id="taskReminderDate" class="form-control <?= session('errors.taskReminderDate') ? 'is-invalid' : '' ?>" value="<?= old('taskReminderDate') ?>"><br>
                <div class="invalid-feedback">
                  <?= session('errors.taskReminderDate') ?? '' ?>
                </div>
              </div>
          </div>

          <div class="mb-3 col-md-4">
            <label for="taskColor" class="form-label">Color de la Tarea:</label>
            <input type="color" name="taskColor" id="taskColor" class="form-control form-control-color <?= session('errors.taskColor') ? 'is-invalid' : '' ?>" value="<?= old('taskColor') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.taskColor') ?? '' ?>
            </div>
          </div>
          

          <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
          <?php endif; ?>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" form="taskCreateForm">Crear Tarea</button>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>
<?= $this->section('contenido') ?>

<div class="container d-flex justify-content-between align-items-center my-2">
  <h2 class="text-center">Tus tareas</h2>
  <button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#newTask">
    Nueva Tarea
  </button>
</div>

<div class="container">
  <div id="mensaje-tareas" class="alert d-none" role="alert"></div>          
</div>

  <div class="container bg-dark p-3 rounded text-light ">
    <?php foreach ($tasks as $task): ?>
    <div class="card mb-2 text-light p-3" style="background-color:<?= $task['task_color'] ?>">
      <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 p-3">
        <div class="d-flex flex-column justify-content-between">
          <div>
            <h5 class="mb-1"> <?= esc($task['task_title'])?></h5>
            <p class="mb-1 text-break"><?= esc($task['task_desc']) ?></p>
          </div>
          <span class="badge bg-<?= $task['task_priority'] === 'Alta' ? 'danger' : ($task['task_priority'] === 'Media' ? 'warning' : 'success') ?> w-100 text-center mb-2"> <?= esc($task['task_priority'] . " ") ?> Prioridad</span>
        </div>

        <div class="w-50">
          <h6 class="mb-2">Subtareas</h6>
          <ul class="list-group list-group-flush">
            <?php foreach ($task['subtasks'] as $sub): ?>
              <li class="list-group-item bg-secondary text-light border-light p-1">
                <?= esc($sub['title'] . ' ' . $sub['subtask_state']) ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
          
        <div class="d-flex flex-column align-items-center justify-content-between text-end">
          <p class="mb-2">🕒 Vence: <strong> <?= esc($task['task_expiry']) ?> </strong></p>
          <div class="btn-group mb-2" role="group">
            <button class="btn btn-editar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>" >✏️ Editar</button>
            <button class="btn btn-eliminar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">🗑️ Eliminar</button>
            <button class="btn btn-archivar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">📦 Archivar</button>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">¿Eliminar tarea?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        Esta acción no se puede deshacer. ¿Estás seguro que deseas eliminar esta tarea?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btnConfirmarEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmarArchivarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">¿Archivar tarea?</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro que deseas archivar esta tarea?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btnConfirmarArchivar" type="button" class="btn btn-primary">Archivar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmarEditarModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Tarea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button id="btnConfirmarEditar" type="button" class="btn btn-primary">Editar</button>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('public/scripts/funcionesTarea.js') ?>"></script>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Mostrar el modal si hay errores de validación
    <?php if (session('errors')): ?>
      var myModal = new bootstrap.Modal(document.getElementById('newTask'));
      myModal.show();

      <?php if (session('errors.taskReminderDate')): ?>
        document.getElementById('taskReminder').checked = true;
      <?php endif ?>
    <?php endif; ?>

    // Script para mostrar u ocultar los campos adicionales
    const checkbox = document.getElementById('taskReminder');
    const additionalFields = document.querySelector('.additional-fields');

    function toggleReminderFields() {
      if (checkbox.checked) {
        additionalFields.style.display = 'block';
      } else {
        additionalFields.style.display = 'none';
      }
    }

    // Ejecutar una vez al inicio
    toggleReminderFields();

    // Cambiar visibilidad al cambiar el checkbox
    checkbox.addEventListener('change', toggleReminderFields);
  });
</script>

<script>
  <?php if (session('success')): ?>
    mostrarMensaje('mensaje-success', <?= json_encode(session('success')) ?>, 'success');
  <?php endif ?>
</script>

<?= $this->endSection() ?>