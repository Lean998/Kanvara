function mostrarMensaje(conte, texto, tipo = 'success') {
    const contenedor = document.getElementById(`${conte}`);
    contenedor.textContent = texto;
    contenedor.className = `alert alert-${tipo}`;
    contenedor.classList.remove('d-none');

    setTimeout(() => {
      contenedor.classList.add('d-none');
    }, 3500);
  }
