document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('.subtarea-item');

    
    items.forEach(item => {
      item.addEventListener('click', () => {
        const desc = item.getAttribute('data-desc');
        const state = item.getAttribute('data-state');
        const priority = item.getAttribute('data-priority');
        const expiry = item.getAttribute('data-expiry');
        const comment = item.getAttribute('data-comment');
        const responsible = item.getAttribute('data-responsible');
        const id = item.getAttribute('data-id');
  
        // Rellenar datos del modal
        document.getElementById('modalSubtaskDesc').textContent = desc;
        document.getElementById('modalSubtaskState').textContent = state;
        document.getElementById('modalSubtaskPriority').textContent = priority;
        document.getElementById('modalSubtaskExpiry').textContent = expiry;
        document.getElementById('modalSubtaskComment').textContent = comment;
        document.getElementById('modalSubtaskResponsible').textContent = responsible;
  
        // Asignar IDs a los inputs ocultos
        document.getElementById('estadoSubtareaId').value = id;
        document.getElementById('estadoSubtareaId2').value = id;
        document.getElementById('estadoSubtareaId3').value = id;
      });
    });
  });
  