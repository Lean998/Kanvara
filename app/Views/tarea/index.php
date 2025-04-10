
<?= $this->extend('plantilla/layout')?>
<?= $this->section('botones') ?>
<button type="button" class="btn btn-primary m-2" data-bs-toggle="modal" data-bs-target="#newTask">
  Nueva Tarea
</button>

<?php if (session('success')): ?>
            <div class="alert alert-success"><?= session('success') ?></div>
<?php endif; ?>
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
    <h2 class="mb-4">Tus tareas</h2>

    <div class="container bg-dark p-4 rounded text-light ">
      <!-- Tarea 1 -->
      <div class="card mb-3 bg-secondary text-light p-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 p-3">
          <!-- Izquierda: Info principal -->
          <div class="d-flex flex-column justify-content-between">
            <div>
              <h5 class="mb-1">💻 Terminar proyecto Kanbara</h5>
              <p class="mb-1 text-break">Completar funcionalidades esenciales.</p>
            </div>
            <span class="badge bg-danger w-100 text-center mb-2">Alta prioridad</span>
          </div>
          <!-- Centro: Subtareas -->
          <div class="w-50">
            <h6 class="mb-2">Subtareas</h6>
            <ul class="list-group list-group-flush">
              <li class="list-group-item bg-secondary text-light border-light p-1">✔️ Validar formulario</li>
              <li class="list-group-item bg-secondary text-light border-light p-1">📝 Crear vista de listado</li>
              <li class="list-group-item bg-secondary text-light border-light p-1">🔔 Agregar recordatorio</li>
              <li class="list-group-item bg-secondary text-light border-light p-1">📁 Adjuntar archivo</li>
            </ul>
          </div>
          <!-- Derecha: Fecha y botones -->
          <div class="d-flex flex-column align-items-center justify-content-between text-end">
            <p class="mb-2">🕒 Vence: <strong>2025-04-20 23:59</strong></p>
            <div class="btn-group mb-2" role="group">
              <button class="btn btn-sm btn-outline-light">✏️ Editar</button>
              <button class="btn btn-sm btn-outline-light">🗑️ Eliminar</button>
              <button class="btn btn-sm btn-outline-light">📦 Archivar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-3 bg-secondary text-light p-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 p-3">
          <!-- Izquierda: Info principal -->
          <div class="d-flex flex-column justify-content-between">
            <div>
              <h5 class="mb-1">🛒 Hacer las compras</h5>
              <p class="mb-1 text-break">Comprar comida y útiles para la semana.</p>
            </div>
            <span class="badge bg-warning w-100 text-center mb-2">Prioridad: Normal</span>
          </div>
          <!-- Centro: Subtareas -->
          <div class="w-50">
            <h6 class="mb-2">Subtareas</h6>
            <ul class="list-group list-group-flush">
              <li class="list-group-item bg-secondary text-light border-light p-1">✔️ Comprar verduras</li>
              <li class="list-group-item bg-secondary text-light border-light p-1">📝 Comprar articulos de limpieza</li>
            </ul>
          </div>
          <!-- Derecha: Fecha y botones -->
          <div class="d-flex flex-column align-items-center  text-end">
            <p class="mb-2">🕒 Vence: <strong>2025-04-12 20:00</strong></p>
            <div class="btn-group mb-2" role="group">
              <button class="btn btn-sm btn-outline-light">✏️ Editar</button>
              <button class="btn btn-sm btn-outline-light">🗑️ Eliminar</button>
              <button class="btn btn-sm btn-outline-light">📦 Archivar</button>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-3 bg-secondary text-light p-3">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 p-3">
          <!-- Izquierda: Info principal -->
          <div class="d-flex flex-column justify-content-between">
            <div>
              <h5 class="mb-1">🧠 Rutina de aim</h5>
              <p class="mb-1 text-break">
                <!-- Este párrafo puede quedar vacío sin afectar el layout -->
              </p>
            </div>
            <span class="badge bg-info w-100 text-center mb-2">Prioridad: Normal</span>
          </div>
          <!-- Centro: Subtareas -->
          <div class="w-50">
            <h6 class="mb-2">Subtareas</h6>
            <ul class="list-group list-group-flush">
              <li class="list-group-item bg-secondary text-light border-light p-1">Comprar verduras</li>
              <li class="list-group-item bg-secondary text-light border-light p-1">Comprar verduras</li>
            </ul>
          </div>
          <!-- Derecha: Fecha y botones -->
          <div class="d-flex flex-column align-items-center  text-end">
            <p class="mb-2">🕒 Vence: <strong>2025-04-12 20:00</strong></p>
            <div class="btn-group mb-2" role="group">
              <button class="btn btn-sm btn-outline-light">✏️ Editar</button>
              <button class="btn btn-sm btn-outline-light">🗑️ Eliminar</button>
              <button class="btn btn-sm btn-outline-light">📦 Archivar</button>
            </div>
          </div>
        </div>
      </div>
    </div>
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
<?= $this->endSection() ?>