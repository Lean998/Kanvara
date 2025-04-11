<?php 
  if (!session('user_id'))  {
    return view('auth/login');
}
?>

<?= $this->extend('plantilla/layout')?>
<?= $this->section('contenido') ?>

<div class="container">
  <div id="mensaje-success" class="alert d-none" role="alert"></div>         
</div>

<section class="container mt-5">
  <article class="card text-light p-4 rounded-3" style="background-color:<?= $task['task_color'] ?>">

    
    <div class="d-flex justify-content-between align-items-start flex-wrap mb-4">
      <h3 class="mb-0"><?= esc($task['task_title']) ?></h3>
      <div class="text-end">
        <p class="mb-1">🕒 <strong>Vence:</strong> <?= date('d/m/Y', strtotime($task['task_expiry'])) ?></p>
        <span class="badge bg-<?= $task['task_priority'] === 'Alta' ? 'danger' : ($task['task_priority'] === 'Media' ? 'warning' : 'success') ?>">
          <?= esc($task['task_priority']) ?> Prioridad
        </span>
      </div>
    </div>

    
    <section class="mb-4">
      <h5>📄 Subtareas</h5>
      <?php if (!empty($task['subtasks'])): ?>
        <ul class="list-group list-group-flush">
          <?php foreach ($task['subtasks'] as $sub): ?>
            <li class="list-group-item bg-secondary text-light border-light p-2 d-flex justify-content-between align-items-center">
              <?= esc($sub['title']) ?>
              <span><?= esc($sub['subtask_state']) ?></span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-light">Esta tarea no tiene subtareas.</p>
      <?php endif; ?>
    </section>

    
    <div class="d-flex justify-content-between align-items-end flex-wrap mt-4 gap-3">
      <div>
        <h5>📝 Descripción</h5>
        <p class="text-break mb-0"><?= esc($task['task_desc']) ?></p>
      </div>
      <div>
        <h5>🕒 Recordatorio</h5>
        <p class="mb-0"><?= date('d/m/Y', strtotime($task['task_reminder'])) ?></p>
      </div>
      <div class="btn-group bg-light rounded" role="group">
        <button class="btn btn-editar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">✏️ Editar</button>
        <button class="btn btn-eliminar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">🗑️ Eliminar</button>
        <button class="btn btn-archivar btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">📦 Archivar</button>
        <button class="btn btn-newSubtask btn-sm btn-outline-dark" data-task-id="<?= $task['task_id'] ?>">&#10133; Nueva Subtarea</button>
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
        <form action='subtareas/crear-subtarea' id="subtaskCreateForm" method="post" class="container mt-4 p-3 border rounded shadow-sm bg-light">
          <?= csrf_field() ?>
          <div class="mb-3">
            <label for="subtaskTitle" class="form-label">Titulo:</label>
            <input type="text" name="subtaskTitle" id="subtaskTitle" class="form-control <?= session('errors.subtaskTitle') ? 'is-invalid' : '' ?>" value="<?= old('subtaskTitle') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.subtaskTitle') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="subtaskDesc" class="form-label">Descripcion:</label>
            <input type="text" name="subtaskDesc" id="subtaskDesc" class="form-control <?= session('errors.subtaskDesc') ? 'is-invalid' : '' ?>" value="<?= old('subtaskDesc') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.subtaskDesc') ?? '' ?>
            </div>
          </div>
          
          
          <div class="row mb-3">
            <div class="col-md-6">
              <label for="subtaskState" class="form-label">Estado:</label>
              <select name="subtaskState" id="subtaskState" class="form-select" required>
                <option value="Definido" <?= old('subtaskState') === 'Definido' ? 'selected' : '' ?>>Definido</option>
                <option value="En proceso" <?= old('subtaskState') === 'En proceso' ? 'selected' : '' ?>>En proceso</option>
                <option value="Completada" <?= old('subtaskState') === 'Completada' ? 'selected' : '' ?>>Completada</option>
              </select>
              <div class="invalid-feedback">
                <?= session('errors.subtaskState') ?? '' ?>
              </div>
            </div>
            
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
          </div>

          <div>
            <label for="subtaskExpiry" class="form-label">Fecha limite:</label>
            <input type="datetime-local" min="<?= date('Y-m-d') ?>" name="subtaskExpiry" id="subtaskExpiry" class="form-control <?= session('errors.subtaskExpiry') ? 'is-invalid' : '' ?>" value="<?= old('subtaskExpiry') ?>"><br>
            <div class="invalid-feedback">
              <?= session('errors.subtaskExpiry') ?? '' ?>
            </div>
          </div>

          <div class="mb-3">
            <label for="subtaskcomment" class="form-label">Comentario:</label>
            <input type="text" name="subtaskcomment" id="subtaskcomment" class="form-control <?= session('errors.subtaskcomment') ? 'is-invalid' : '' ?>" value="<?= old('subtaskcomment') ?>" required><br>
            <div class="invalid-feedback">
              <?= session('errors.subtaskcomment') ?? '' ?>
            </div>
          </div>

          <div>
            <label for="taskresponsible" class="form-label">Prioridad:</label>
            <select name="taskresponsible" id="taskresponsible" class="form-select" required>
              <?php foreach($colaboradores as $colaborador): ?>
                <option value="<?= $colaborador['user_id']?>"> <?= $colaborador['user_name'] ?></option>
              <?php endforeach; ?>
            </select>
            <div class="invalid-feedback">
              <?= session('errors.taskresponsible') ?? '' ?>
            </div>
          </div>

          <?php if (session('error')): ?>
            <div class="alert alert-danger"><?= session('error') ?></div>
          <?php endif; ?>
        </form>
      </div>
      <div class="modal-footer d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-primary" form="subtaskCreateForm">Crear Subtarea</button>
      </div>
    </div>
  </div>
</div>

<script>
  const BASE_URL = "<?= base_url() ?>";
</script>
<script src="<?= base_url('public/scripts/funcionesTarea.js') ?>"></script>

<?= $this->endSection() ?>