// ======================================================
// Script: Filtrar frecuencias de RAM según tipo de tarjeta madre
// ======================================================

document.addEventListener("DOMContentLoaded", function() {
  // ===== Obtener input de tipo de RAM y grupos de frecuencias =====
  const tipoInput = document.querySelector("input[name='tipo_tarjeta_madre']");
  const grupos = document.querySelectorAll(".frecuencia-grupo");

  // Si no existe el input, salir para evitar errores
  if (!tipoInput) return;

  // ===== Función para mostrar/ocultar grupos según tipo =====
  function filtrarFrecuencias() {
    // Obtener valor del input en mayúsculas y sin espacios
    const tipo = tipoInput.value.toUpperCase().trim(); // ej. DDR3, DDR4, etc.

    // Recorrer todos los grupos y mostrar solo los que coincidan con el tipo
    grupos.forEach(grupo => {
      grupo.style.display = grupo.dataset.tipo === tipo ? "block" : "none";
    });
  }

  // ===== Eventos =====
  // Filtrar cuando el usuario cambia el valor del input
  tipoInput.addEventListener("input", filtrarFrecuencias);

  // Filtrar al cargar la página si ya hay un valor en el input
  filtrarFrecuencias();
});
