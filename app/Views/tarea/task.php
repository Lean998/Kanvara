<?php 
use App\Models\UserModel;
if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>

<section class="container mt-5">
  <article class="card text-light p-4 rounded-3" style="background-color:<?= $task['task_color'] ?>">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start flex-wrap mb-4 gap-3 mx-0">
      <h3 class="mb-0 text-break"><?= esc($task['task_title']) ?></h3>
      <div class="text-center text-md-end">
        <p class="mb-2 text-start"> Estado: <strong> <?= esc($task['task_state']) ?> </strong></p>
        <div class="d-flex flex-column align-items-start mb-5">
            <p class="mb-2">🕒 Vence: <strong> <?= esc($task['task_expiry']) ?> </strong></p>
            <?php if ($task['task_reminder'] != null ): ?>
              <p class="mb-2">🕒 Recordatorio: <?= esc($task['task_reminder']) ?></p>
            <?php endif; ?>
          <?php if($task['task_state'] != "Completada"): ?>
          <button class="mb-2 btn w-100 btn-finalizar btn-sm btn-outline-dark btn-light" data-task-id="<?= $task['task_id'] ?>">Finalizar</button>
          <?php endif; ?>
          <span class="badge bg-<?= $task['task_priority'] === 'Alta' ? 'danger' : ($task['task_priority'] === 'Normal' ? 'warning' : 'success') ?> w-100 text-center mb-2"> Prioridad <?= esc(" ".$task['task_priority']) ?></span>
        </div>
      </div>
    </div>

    <section class="mb-4">
      <h5>📄 Subtareas</h5>
      <?php if (!empty($task['subtasks'])): ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($task['subtasks'] as $sub): ?>
            <li class="list-group-item text-light border-light p-2 d-flex justify-content-between align-items-center" style="background-color:<?= $task['task_color'] ?>">
              <span class="text-light subtarea-item text-break"
                    role="button"
                    data-bs-toggle="modal" 
                    data-bs-target="#subtaskModal"
                    data-desc="<?= esc($sub['subtask_desc']) ?>"
                    data-state="<?= esc($sub['subtask_state']) ?>"
                    data-priority="<?= esc($sub['subtask_priority']) ?>"
                    data-expiry="<?= esc($sub['subtask_expiry']) ?>"
                    data-comments='<?= htmlspecialchars(json_encode($sub["comentarios"] ?? []), ENT_QUOTES, "UTF-8") ?>'
                    data-responsible="<?= $sub['user_id'] != null ? $sub['user_name'] : 'Sin responsable' ?>"
                    data-collaborators='<?= htmlspecialchars(json_encode($sub["colaboradores"] ?? []), ENT_QUOTES, "UTF-8") ?>'
                    data-id="<?= esc($sub['subtask_id'])?>">
                    <?= esc(data: $sub['subtask_desc']) ?> | <strong>Estado:</strong> <?= esc($sub['subtask_state']) ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-light text-center">Esta tarea no tiene subtareas.</p>
      <?php endif; ?>
    </section>

    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-end flex-wrap mt-4 gap-3">
      <div class="w-100 w-lg-25">
        <h5>📝 Descripción</h5>
        <p class="text-break mb-0"><?= esc($task['task_desc']) ?></p>
      </div>
      <div class="w-100 w-lg-25">
        
      </div>
      <div class="btn-group bg-light rounded flex-wrap gap-2 w-100 w-lg-auto justify-content-center justify-content-lg-end" role="group">
        <button class="btn btn-editar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">✏️ Editar</button>
        <button class="btn btn-eliminar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">🗑️ Eliminar</button>
        <button class="btn btn-archivar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">📦 Archivar</button>
        <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newtaskCollaborator">Agregar Colaborador</button>
        <button class="btn btn-newSubtask btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#newSubtask" data-task-id="<?= $task['task_id'] ?>">➕ Nueva Subtarea</button>
      </div>
    </div>

  </article>
</section>

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

<div class="modal fade" id="confirmarEditarModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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


<div class="modal fade" id="newSubtask" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newSubtaskLabel">Nueva Subtarea</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url()?>subtask/crear-subtarea" method="post" id="subtaskCreateForm" class="container mt-4 p-3 border rounded shadow-sm bg-light">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label for="subtaskDesc" class="form-label">Descripción:</label>
            <input type="text" name="subtaskDesc" id="subtaskDesc" class="form-control <?= session('errors.subtaskDesc') ? 'is-invalid' : '' ?>" value="<?= old('subtaskDesc') ?>" required>
            <div class="invalid-feedback">
              <?= session('errors.subtaskDesc') ?? '' ?>
            </div>
          </div>
          
          <div class="row mb-3 g-3">
            <div class="col-12 col-md-6">
              <label for="subtaskState" class="form-label <?= session('errors.subtaskState') ? 'is-invalid' : '' ?>">Estado:</label>
              <select name="subtaskState" id="subtaskState" class="form-select" required>
                <option value="" <?= old('subtaskState') === '' ? 'selected' : '' ?>>Seleccione una opción</option>
                <option value="Definida" <?= old('subtaskState') === 'Definida' ? 'selected' : '' ?>>Definida</option>
                <option value="En proceso" <?= old('subtaskState') === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                <option value="Completada" <?= old('subtaskState') === 'Completada' ? 'selected' : '' ?>>Completada</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.subtaskState') ?? '' ?>
              </div>
            </div>
            
            <div class="col-12 col-md-6">
              <label for="subtaskPriority" class="form-label">Prioridad:</label>
              <select name="subtaskPriority" id="subtaskPriority" class="form-select <?= session('errors.subtaskPriority') ? 'is-invalid' : '' ?>">
                <option value="Baja" <?= old('subtaskPriority') === 'baja' ? 'selected' : '' ?>>Baja</option>
                <option value="Normal" <?= old('subtaskPriority') === 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="Alta" <?= old('subtaskPriority') === 'alta' ? 'selected' : '' ?>>Alta</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.subtaskPriority') ?? '' ?>
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label for="subtaskExpiry" class="form-label">Fecha límite:</label>
            <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="subtaskExpiry" id="subtaskExpiry" class="form-control <?= session('errors.subtaskExpiry') ? 'is-invalid' : '' ?>" value="<?= old('subtaskExpiry') ?>">
            <div class="invalid-feedback">
              <?= session('errors.subtaskExpiry') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="subtaskComment" class="form-label">Comentario:</label>
            <input type="text" name="subtaskComment" id="subtaskComment" class="form-control <?= session('errors.subtaskComment') ? 'is-invalid' : '' ?>" value="<?= old('subtaskComment') ?>" required>
            <div class="invalid-feedback">
              <?= session('errors.subtaskComment') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="subtaskResponsible" class="form-label">Responsable:</label>
            <?php if(!empty($colab)): ?>
              <select name="subtaskResponsible" id="subtaskResponsible" class="form-select <?= session('errors.subtaskResponsible') ? 'is-invalid' : '' ?>" required>
                <?php foreach($colab as $colaborador): ?>
                  <option value="<?= $colaborador['user_id']?>"> <?= $colaborador['user_name'] ?></option>
                <?php endforeach; ?>
              </select>
            <?php else : ?>
              <p class="text-muted text-center">Sin colaboradores</p>
            <?php endif; ?>
            <div class="invalid-feedback">
              <?= session('errors.subtaskResponsible') ?? '' ?>
            </div>
          </div>
          
          <input type="text" class="d-none" name="task_id" value="<?= $task['task_id'] ?>">
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" form="subtaskCreateForm">Crear Subtarea</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Subtarea -->
<div class="modal fade" id="subtaskModal" tabindex="-1" aria-labelledby="subtaskModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-light text-dark">
      <div class="modal-header">
        <h5 class="modal-title" id="subtaskModalLabel">📄 Detalle de la Subtarea</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <p class="text-break"><strong>Descripción:</strong> <span id="modalSubtaskDesc"></span></p>
        <p><strong>Estado:</strong> <span id="modalSubtaskState"></span></p>
        <p><strong>Prioridad:</strong> <span id="modalSubtaskPriority"></span></p>
        <p><strong>Vencimiento:</strong> <span id="modalSubtaskExpiry"></span></p>
        <div class="mb-3">
          <strong>Comentarios:</strong>
          <ul id="modalSubtaskComments" class="list-group list-group-flush"></ul>
        </div>
        <div class="mb-3">
          <strong>Colaboradores:</strong>
          <ul id="modalSubtaskCollaborators" class="list-group list-group-flush"></ul>
        </div>
        <p><strong>Responsable:</strong> <span id="modalSubtaskResponsible"></span></p>
      </div>
      <div class="modal-footer flex-wrap gap-2">
        <div id="estadoSubtareaControles" class="d-flex gap-2 flex-wrap">
          <form method="get" action="<?= base_url('subtask/editar-subtarea')?>" class="d-inline">
            <input type="hidden" name="subtask" id="estadoSubtareaId">
            <button type="submit" class="btn btn-warning btn-sm">✏️ Editar</button>
          </form>

          <form method="post" action="<?= base_url('subtask/eliminar-subtarea') ?>" class="d-inline">
            <input type="hidden" name="subtask_id" id="estadoSubtareaId2">
            <button type="submit" class="btn btn-danger btn-sm">🗑️ Eliminar</button>
          </form>

          <form method="get" action="<?= base_url('subtask/agregar-colaborador')?>" class="d-inline">
            <input type="hidden" name="subtask" id="estadoSubtareaId3">
            <button type="submit" class="btn btn-success btn-sm"> Agregar Colaborador</button>
          </form>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Colaborador Tarea-->
<div class="modal fade" id="newtaskCollaborator" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newtaskCollaboratorLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="newtaskCollaboratorLabel">Agregar Colaborador</h1>
        <button type="button" class="btn-close btn-outline-danger" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form method="post" action="<?= base_url('tareas/agregar-colaborador') ?>" class="d-inline" id="addCollaboratorTaskForm">
          <label for="taskCollaborator" class="form-label">Correo:</label>
          <input type="hidden" name="task_id" value="<?= $task['task_id'] ?>">
          <input type="email" name="taskCollaborator" id="taskCollaborator" class="form-control <?= session('errors.taskCollaborator') ? 'is-invalid' : '' ?>" value="<?= old('taskCollaborator') ?>" required>
          <div class="invalid-feedback">
            <?= session('errors.taskCollaborator') ?? '' ?>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary btn-sm" form="addCollaboratorTaskForm">Agregar Colaborador</button>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-light">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteLabel">Confirmar eliminación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Estás seguro de que querés eliminar este elemento?
      </div>
      <div class="modal-footer">
        <form id="deleteForm" method="POST">
          <input type="hidden" name="id" id="deleteElementId">
          <button type="submit" class="btn btn-danger">Eliminar</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        </form>
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

<script src="<?= base_url('public/scripts/funcionesTarea.js') ?>"></script>
<script src="<?= base_url('public/scripts/funcionesSubtarea.js') ?>"></script>
<script>
  <?php if (session('success')): ?>
    mostrarMensaje('mensaje-success', <?= json_encode(session('success')) ?>, 'success');
  <?php endif ?>
</script>
<script>
  <?php if (session('error')): ?>
    mostrarMensaje('mensaje-success', <?= json_encode(session('error')) ?>, 'danger');
  <?php endif ?>
</script>

<?= $this->endSection() ?>