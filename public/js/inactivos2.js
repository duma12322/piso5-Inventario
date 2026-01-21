// ======================================================
// Script: Toggle de visibilidad de secciones/divs
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
  // Selecciona todos los botones con la clase .toggle-btn
  document.querySelectorAll(".toggle-btn").forEach(btn => {
    // Agrega un evento click a cada botón
    btn.addEventListener("click", () => {
      // Obtiene el id del div objetivo desde el atributo data-target del botón
      const targetId = btn.dataset.target;

      // Busca el div correspondiente
      const div = document.getElementById(targetId);

      // Alterna la clase "d-none" para ocultar o mostrar el div
      // "d-none" normalmente se usa en Bootstrap para display: none
      div.classList.toggle("d-none");
    });
  });
});
