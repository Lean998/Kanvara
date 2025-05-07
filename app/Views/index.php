<?php 
  $sesion = session();
  if(!isset($_SESSION['user_id']) OR !$sesion->get("user_id")){
    return view("auth/login");
  }
?>

<?= $this->extend('plantilla/layout') ?>
<?= $this->section('botones') ?>

<!-- Modal Crear Tarea -->
<div class="modal fade" id="newTask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newTaskLabel">Nueva Tarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action='tareas/crear-tarea' id="taskCreateForm" method="post" class="container mt-4 p-3 border rounded shadow-sm bg-light">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label for="taskTitle" class="form-label">Título:</label>
            <input type="text" name="taskTitle" id="taskTitle" class="form-control <?= session('errors.taskTitle') ? 'is-invalid' : '' ?>" value="<?= old('taskTitle') ?>" required>
            <div class="invalid-feedback">
              <?= session('errors.taskTitle') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="taskDesc" class="form-label">Descripción:</label>
            <input type="text" name="taskDesc" id="taskDesc" class="form-control <?= session('errors.taskDesc') ? 'is-invalid' : '' ?>" value="<?= old('taskDesc') ?>" required>
            <div class="invalid-feedback">
              <?= session('errors.taskDesc') ?? '' ?>
            </div>
          </div>
          
          <div class="row mb-3 g-3">
            <div class="col-12 col-md-6">
              <label for="taskState" class="form-label">Estado:</label>
              <select name="taskState" id="taskState" class="form-select <?= session('errors.taskState') ? 'is-invalid' : '' ?>">
                <option value="" <?= old('taskState') === '' ? 'selected' : '' ?> disabled>Seleccione una opción</option>
                <option value="Definida" <?= old('taskState') === 'Definida' ? 'selected' : '' ?>>Definida</option>
                <option value="En proceso" <?= old('taskState') === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                <option value="Completada" <?= old('taskState') === 'Completada' ? 'selected' : '' ?>>Completada</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.taskState') ?? '' ?>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <label for="taskPriority" class="form-label">Prioridad:</label>
              <select name="taskPriority" id="taskPriority" class="form-select <?= session('errors.taskPriority') ? 'is-invalid' : '' ?>">
                <option value="Baja" <?= old('taskPriority') === 'baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Normal" <?= old('taskPriority') === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="Alta" <?= old('taskPriority') === 'alta' ? 'selected' : '' ?>>Alta</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.taskPriority') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input bg-input" id="taskReminder" name="taskReminder" value="<?= old('taskReminder') ?>">
            <label class="form-check-label mx-2" for="taskReminder">Agregar recordatorio</label>
          </div>

          <div class="row mb-3 g-3">
            <div class="col-12 col-md-6">
              <div class="row mb-3 additional-fields" style="display: none;">
                <div>
                  <label for="taskReminderDate" class="form-label">Fecha del recordatorio:</label>
                  <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="taskReminderDate" id="taskReminderDate" class="form-control <?= session('errors.taskReminderDate') ? 'is-invalid' : '' ?>" value="<?= old('taskReminderDate') ?>">
                  <div class="invalid-feedback">
                    <?= session('errors.taskReminderDate') ?? '' ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-md-6">
              <label for="taskExpiry" class="form-label">Fecha límite:</label>
              <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="taskExpiry" id="taskExpiry" class="form-control <?= session('errors.taskExpiry') ? 'is-invalid' : '' ?>" value="<?= old('taskExpiry') ?>" required>
              <div class="invalid-feedback">
                <?= session('errors.taskExpiry') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="mb-3 col-12 col-md-4">
            <label for="taskColor" class="form-label">Color de la Tarea:</label>
            <input type="color" name="taskColor" id="taskColor" class="form-control form-control-color <?= session('errors.taskColor') ? 'is-invalid' : '' ?>" value="<?= old('taskColor') ?>" required>
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

  <!-- Contenedor Ordenar Tareas -->
  <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center my-2 gap-3">
    <h2 class="text-center text-md-start mb-0"><?= $subtitulo?></h2>
    <div class="d-flex flex-wrap justify-content-center justify-content-md-end align-items-center gap-2">
      <button type="button" class="btn btn-primary m-2 rounded-3" data-bs-toggle="modal" data-bs-target="#newTask">
        Nueva Tarea
      </button>
      <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#invitationModal">
        Tengo un Código de Invitación
      </button>
      <div class="dropdown d-inline-block">
        <button class="btn btn-outline-light btn-light text-dark dropdown-toggle" type="button" id="dropdownOrdenar" data-bs-toggle="dropdown" aria-expanded="false">
          Ordenar
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownOrdenar">
          <li><a class="dropdown-item" href="<?= base_url() . session('opcion') . '?ordenar=task_expiry' ?>">Por vencimiento</a></li>
          <li><a class="dropdown-item" href="<?= base_url() . session('opcion') . '?ordenar=task_priority' ?>">Por prioridad</a></li>
        </ul>
      </div>
    </div>
  </div>

  <!-- Contenedor para mostrar mensajes -->
  <div class="container">
    <div id="mensaje-tareas" class="alert d-none" role="alert"></div>          
  </div>

  <!-- Contenedor Tareas -->
  <div class="container bg-dark p-3 text-light rounded-3">
    <?php if (!empty($tasks)): ?>
    <?php foreach ($tasks as $task): ?>
    
      <div class="card mb-3 text-light rounded-3" style="background-color:<?= $task['task_color'] ?>">
        <div class="row g-3 p-3">
            <!-- Sección del título y descripción -->
            <?php if (session('opcion') !== 'tareas/tareas-eliminadas' ): ?>
              <div class="col-12 col-md-6 col-lg-4">
                <a href="<?= base_url('tareas/ver/' .$task['task_id']) ?>" style="text-decoration: none; color: inherit;">
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 text-break"><?= esc($task['task_title'])?></h5>
                        <p class="mb-1 text-break"><?= esc($task['task_desc']) ?></p>
                        <span class="badge bg-<?= $task['task_priority'] === 'Alta' ? 'danger' : ($task['task_priority'] === 'Normal' ? 'warning' : 'success') ?> w-100 text-center mb-2"> Prioridad <?= esc(" ".$task['task_priority']) ?></span>
                    </div>
                </a>
              </div>
            <?php else: ?>
              <div class="col-12 col-md-6 col-lg-4">
                    <div class="d-flex flex-column">
                        <h5 class="mb-1 text-break"><?= esc($task['task_title'])?></h5>
                        <p class="mb-1 text-break"><?= esc($task['task_desc']) ?></p>
                        <span class="badge bg-<?= $task['task_priority'] === 'Alta' ? 'danger' : ($task['task_priority'] === 'Normal' ? 'warning' : 'success') ?> w-100 text-center mb-2"> Prioridad <?= esc(" ".$task['task_priority']) ?></span>
                    </div>
              </div>
            <?php endif; ?>

            <!-- Sección de subtareas -->
            <?php if (session('opcion') !== 'tareas/tareas-eliminadas' ): ?>
            <div class="col-12 col-md-6 col-lg-5">
                <a  href="<?= base_url('tareas/ver/' .$task['task_id']) ?>" style="text-decoration: none; color: inherit;">
                    <h6 class="mb-2">Subtareas</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($task['subtasks'] as $sub): ?>
                            <li class="list-group-item border-light p-1 text-light text-break" style="background-color:<?= $task['task_color'] ?>">
                                <?= esc($sub['subtask_desc']) . ' | <strong>Estado: </strong>  ' . esc($sub['subtask_state']) ?> 
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </a>
            </div>
            <?php else: ?>
              <div class="col-12 col-md-6 col-lg-5">
                    <h6 class="mb-2">Subtareas</h6>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($task['subtasks'] as $sub): ?>
                            <li class="list-group-item border-light p-1 text-light text-break" style="background-color:<?= $task['task_color'] ?>">
                                <?= esc($sub['subtask_desc']) . ' | <strong>Estado: </strong>  ' . esc($sub['subtask_state']) ?> 
                            </li>
                        <?php endforeach; ?>
                    </ul>
            </div>
            <?php endif; ?>

            <!-- Sección de vencimiento y botones -->
            <div class="col-12 col-lg-3 d-flex flex-column align-items-center align-items-lg-end justify-content-between text-center text-lg-end">
                <div class="d-flex flex-column mb-5">
                  <span class="text-start mb-2">Estado:<strong> <?= esc($task['task_state']) ?></strong> </span> 
                  <p class="mb-2">🕒 Vence: <strong> <?= esc($task['task_expiry']) ?> </strong></p>
                    <?php if($task['task_state'] != "Completada" AND $task['user_id'] == session('user_id')): ?>
                      <button class="btn btn-finalizar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">Finalizar</button>
                    <?php endif; ?>
                </div>
                <?php if($task['user_id'] == session('user_id')): ?> 
                  <div class="btn-group flex-wrap mb-2 gap-2" role="group">
                    <button class="btn btn-editar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">✏️ Editar</button>
                      <?php if (session('opcion') !== 'tareas/tareas-eliminadas' ): ?>
                    <button class="btn btn-eliminar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">🗑️ Eliminar</button>
                    <?php endif; ?>
                    
                    <?php if (session('opcion') !== 'tareas/tareas-archivadas'): ?>
                      <button class="btn btn-archivar btn-sm btn-outline-light" data-task-id="<?= $task['task_id'] ?>">📦 Archivar</button>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
            </div>
        </div>
      </div>
    <?php endforeach; ?>
    <?php else: ?>
      <?php $msj = explode('-',session('opcion')) ?>
      <p class="text-light text-center">No tienes Tareas <?php if (!empty($msj[1])){echo($msj[1]);}  ?></>
    <?php endif; ?>
  </div>

  <!-- Modal Confirmar Eliminar Tarea -->
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

  <!-- Modal Confirmar Finalizar Tarea -->
  <div class="modal fade" id="confirmarFinalizarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">¿Finalizar tarea?</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          ¿Estás seguro que deseas finalizar esta tarea?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button id="btnConfirmarFinalizar" type="button" class="btn btn-danger">Finalizar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Confirmar Archivar Tarea -->
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

  <!-- Modal Editar Tarea -->
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

  <!-- Modal Aceptar Invitación -->
  <div class="modal fade" id="invitationModal" tabindex="-1" aria-labelledby="invitationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="invitationModalLabel">Ingresar Código de Invitación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
              <?= esc(session()->getFlashdata('error')) ?>
            </div>
          <?php endif; ?>
          <?= form_open('tareas/aceptar-invitacion') ?>
          <div class="mb-3">
            <label for="invitation_code" class="form-label">Código de Invitación</label>
            <input type="text" name="invitation_code" id="invitation_code" class="form-control" placeholder="Ingresa el código" required>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-primary">Enviar Código</button>
          </div>
          <?= form_close() ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
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
<script src="<?= base_url('public/scripts/funcionesTarea.js') ?>"></script>
<script>
  <?php if (session('success')): ?>
    mostrarMensaje('mensaje-success', <?= json_encode(session('success')) ?>, 'success');
  <?php endif ?>
</script>
<?= $this->endSection() ?>