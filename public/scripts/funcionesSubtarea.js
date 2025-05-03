document.addEventListener('DOMContentLoaded', () => {
  const items = document.querySelectorAll('.subtarea-item');

  items.forEach(item => {
    item.addEventListener('click', () => {
      const desc = item.getAttribute('data-desc');
      const state = item.getAttribute('data-state');
      const priority = item.getAttribute('data-priority');
      const expiry = item.getAttribute('data-expiry');
      const responsible = item.getAttribute('data-responsible');
      const id = item.getAttribute('data-id');

      
      let comments = [];
      let collaborators = [];
      
      try {
          comments = JSON.parse(item.getAttribute('data-comments') || '[]');
          if (!Array.isArray(comments)) {
              throw new Error('data-comments no es un array');
          }
      } catch (e) {
          console.error('Error al parsear data-comments:', e, item.getAttribute('data-comments'));
          comments = [];
      }

      try {
          collaborators = JSON.parse(item.getAttribute('data-collaborators') || '[]');
          if (!Array.isArray(collaborators)) {
              throw new Error('data-collaborators no es un array');
          }
      } catch (e) {
          console.error('Error al parsear data-collaborators:', e, item.getAttribute('data-collaborators'));
          collaborators = [];
      }

      // Rellenar datos del modal
      document.getElementById('modalSubtaskDesc').textContent = desc || 'Sin descripción';
      document.getElementById('modalSubtaskState').textContent = state || 'Sin estado';
      document.getElementById('modalSubtaskPriority').textContent = priority || 'Sin prioridad';
      document.getElementById('modalSubtaskExpiry').textContent = expiry || 'Sin vencimiento';
      document.getElementById('modalSubtaskResponsible').textContent = responsible || 'Sin responsable';

      // Rellenar lista de comentarios
      const commentsList = document.getElementById('modalSubtaskComments');
      commentsList.innerHTML = ''; 
      if (comments.length > 0) {
        comments.forEach(comment => {
          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center text-break';
        
          const span = document.createElement('span');
          span.textContent = comment.comments_comment || 'Comentario sin texto';
        
          const deleteBtn = document.createElement('button');
          deleteBtn.className = 'btn btn-sm btn-danger';
          deleteBtn.textContent = 'Eliminar';
        
          deleteBtn.addEventListener('click', () => {
          const deleteForm = document.getElementById('deleteForm');
          const deleteInput = document.getElementById('deleteElementId');
        
          deleteInput.value = comment.comments_id;
          deleteForm.action = `${BASE_URL}comentarios/eliminar`; 
        
          const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
          modal.show();
          });
        
          li.appendChild(span);
          li.appendChild(deleteBtn);
          commentsList.appendChild(li);
        });
      } else {
          const li = document.createElement('li');
          li.className = 'list-group-item text-muted';
          li.textContent = 'Sin comentarios';
          commentsList.appendChild(li);
      }

      // Rellenar lista de colaboradores
      const collaboratorsList = document.getElementById('modalSubtaskCollaborators');
      collaboratorsList.innerHTML = ''; 
      if (collaborators.length > 0) {
        collaborators.forEach(collaborator => {

          const li = document.createElement('li');
          li.className = 'list-group-item d-flex justify-content-between align-items-center text-break';
        
          const span = document.createElement('span');
          span.textContent = collaborator.user_name || 'Nombre no disponible';
        
          const deleteBtn = document.createElement('button');
          deleteBtn.className = 'btn btn-sm btn-danger';
          deleteBtn.textContent = 'Eliminar';

          
          
          deleteBtn.addEventListener('click', () => {
          const deleteForm = document.getElementById('deleteForm');
          const deleteInput = document.getElementById('deleteElementId');
          
          deleteInput.value = collaborator.user_id;
          deleteForm.action = `${BASE_URL}subtask/eliminar-colaborador`; 
          
          const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
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

      // Asignar IDs a los inputs ocultos
      document.getElementById('estadoSubtareaId').value = id || '';
      document.getElementById('estadoSubtareaId2').value = id || '';
      document.getElementById('estadoSubtareaId3').value = id || '';
    });
  });
});