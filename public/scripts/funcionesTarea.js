
let taskAEliminar = null; 
let taskAEditar = null;
let taskAFinalizar = null;             

  document.getElementById('btnConfirmarFinalizar').addEventListener('click', function () {
    if (!taskAFinalizar) return;

    const taskIdFinal = taskAFinalizar.dataset.taskId;

    fetch(`${BASE_URL}tareas/finalizar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `task_id=${encodeURIComponent(taskIdFinal)}`
    })
    
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        mostrarMensaje('mensaje-success', '✅ Tarea Finalizada correctamente.', 'success');
        taskAFinalizar.closest('.card').remove();
        setTimeout(() => {
          window.location.href = `${BASE_URL}`; 
        }, 500);
      } else if(data.warning){
        mostrarMensaje('mensaje-success', data.message, 'warning');
        taskAFinalizar.closest('.card').remove();
        setTimeout(() => {
          window.location.href = `${BASE_URL}`; 
        }, 500);
      } 
      else {
        mostrarMensaje('mensaje-success', data.message, 'danger');
      }
    })
    .catch(err => {
      console.error(err);
      mostrarMensaje('mensaje-success', '❌ Error en la petición.', 'danger');
    });

    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmarFinalizarModal'));
    modal.hide();
  });

  let tareaEditar = null;

document.querySelectorAll('.btn-editar').forEach(btn => {
  btn.addEventListener('click', function () {
    const taskId = this.dataset.taskId;
    tareaEditar = this.closest('.card');

    fetch(`${BASE_URL}tareas/tarea/${taskId}`)
      .then(res => res.json())
      .then(task => {
        const modal = new bootstrap.Modal(document.getElementById('confirmarEditarModal'));
        let collaboratorsList = document.createElement('ul');
        collaboratorsList.className = 'list-group list-group-flush';
        collaboratorsList.innerHTML = ''; 
        const collaborators = task.colaboradores || [];
        if (collaborators.length > 0) {
          collaborators.forEach(collaborator => {
  
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex justify-content-between align-items-center text-break';
          
            const span = document.createElement('span');
            span.textContent = collaborator.user_name || 'Nombre no disponible';
          
            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'btn btn-sm btn-danger';
            deleteBtn.textContent = 'Eliminar';
            deleteBtn.type = 'button'; 
  
            
            
            deleteBtn.addEventListener('click', () => {
            const deleteForm = document.getElementById('ColabdeleteForm');
            const deleteInput = document.getElementById('deleteCollabId');
            
            deleteInput.value = collaborator.user_id;
            deleteForm.action = `${BASE_URL}tareas/eliminar-colaborador`; 
            
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteTaskColabModal'));
            modal.show();
            });
  
            li.appendChild(span);
            li.appendChild(deleteBtn);
            collaboratorsList.appendChild(li);
          });
        } else {
            const li = document.createElement('li');
            li.className = 'list-group-item text-muted';
            li.textContent = 'Sin colaboradores';
            collaboratorsList.appendChild(li);
        }


        document.querySelector('#confirmarEditarModal .modal-body').innerHTML = `
          <form id="formEditarTarea" class="container mt-4 p-3 border rounded shadow-sm bg-light">
            <input type="hidden" name="task_id" value="${task.task_id}">
            <div class="mb-3">
              <label class="form-label" for="task_title_e">Título</label>
              <input type="text" id="task_title_e" name="task_title" class="form-control" value="${task.task_title}">
            </div>
            <div class="mb-3">
              <label class="form-label" for="task_expiry_e">Fecha límite</label>
              <input type="datetime-local" id="task_expiry_e" name="task_expiry" class="form-control" value="${task.task_expiry}">
            </div>
            <div class="mb-3" id="collaborators-container">
              <label class="form-label">Colaboradores</label>
            </div>
            <div class="mb-3">
              <label class="form-label" for="task_color_e">Color</label>
              <input type="color" id="task_color_e" name="task_color" class="form-control form-control-color" value="${task.task_color}">
            </div>
          </form>
        `;
        document.getElementById('collaborators-container').appendChild(collaboratorsList);
        modal.show();
      })
      .catch(err => {
        console.error(err);
        mostrarMensaje('mensaje-success', '❌ Error al obtener los datos de la tarea.', 'danger');
      });
  });
});

document.getElementById('btnConfirmarEditar').addEventListener('click', function () {
  const form = document.getElementById('formEditarTarea');
  if (!form) return;

  const formData = new FormData(form);

  fetch(`${BASE_URL}tareas/editar`, {
    method: 'POST',
    body: new URLSearchParams(formData)
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        mostrarMensaje('mensaje-success', '✅ Tarea actualizada correctamente, refresque para ver los cambios.', 'success');
        bootstrap.Modal.getInstance(document.getElementById('confirmarEditarModal')).hide();
      } else {
        mostrarMensaje('mensaje-success', data.message || '❌ Error al actualizar la tarea.', 'danger');
      }
    })
    .catch(err => {
      console.error(err);
      mostrarMensaje('mensaje-success', '❌ Error en la petición.', 'danger');
    });
});

let tareaArchivar = null;
document.getElementById('btnConfirmarArchivar').addEventListener('click', function () {
    if (!tareaArchivar) return;

    const taskIdArchivar = tareaArchivar.dataset.taskId;
    fetch(`${BASE_URL}tareas/archivar`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `task_id=${encodeURIComponent(taskIdArchivar)}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        mostrarMensaje('mensaje-success', '✅ Tarea archivada correctamente.', 'success');
        tareaArchivar.closest('.card').remove();
      } else {
        mostrarMensaje('mensaje-success', '❌ Error al archivar la tarea.', 'danger');
      }
    })
    .catch(err => {
      console.error(err);
      mostrarMensaje('mensaje-success', '❌ Error en la petición.', 'danger');
    });

    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmarArchivarModal'));
    modal.hide();
  });

  document.querySelectorAll('.btn-eliminar').forEach(btn => {
    btn.addEventListener('click', function () {
      taskAEliminar = this;

      const modal = new bootstrap.Modal(document.getElementById('confirmarEliminarModal'));
      modal.show();
    });
  });

  document.querySelectorAll('.btn-finalizar').forEach(btn => {
    btn.addEventListener('click', function () {
      taskAFinalizar = this;

      const modal = new bootstrap.Modal(document.getElementById('confirmarFinalizarModal'));
      modal.show();
    });
  });

  document.querySelectorAll('.btn-archivar').forEach(btn => {
    btn.addEventListener('click', function () {
      tareaArchivar = this;

      const modal = new bootstrap.Modal(document.getElementById('confirmarArchivarModal'));
      modal.show();
    });
  });