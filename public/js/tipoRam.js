document.addEventListener("DOMContentLoaded", function() {
  const tipoInput = document.querySelector("input[name='tipo_tarjeta_madre']");
  const grupos = document.querySelectorAll(".frecuencia-grupo");

  if (!tipoInput) return; // evita errores si no hay input

  function filtrarFrecuencias() {
    const tipo = tipoInput.value.toUpperCase().trim(); // DDR3, DDR4, etc.
    grupos.forEach(grupo => {
      grupo.style.display = grupo.dataset.tipo === tipo ? "block" : "none";
    });
  }

  // Filtrar al cambiar el tipo de RAM
  tipoInput.addEventListener("input", filtrarFrecuencias);

  // Filtrar al cargar la página (si ya hay un valor)
  filtrarFrecuencias();
});
