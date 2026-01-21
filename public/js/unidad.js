// ======================================================
// Script: Gestión de campos y compatibilidad de unidades ópticas
// ======================================================

document.addEventListener("DOMContentLoaded", function() {
  // ===== Selección de elementos =====
  const tipoSelect = document.querySelector('select[name="tipo_unidad"]'); // Select de tipo de unidad óptica
  const unidadOpticaDiv = document.getElementById("unidad_optica_campos"); // Div con los campos específicos de unidad óptica
  const checkboxes = document.querySelectorAll('input[name="tipos_discos[]"]'); // Checkboxes de tipos de discos (CD, DVD, Blu-ray)

  // ===== Tabla de compatibilidad =====
  // Define qué tipo de discos son compatibles según el tipo de unidad óptica
  const compatibilidad = {
    "CD-ROM": { CD: true, DVD: false, "Blu-ray": false },
    "CD-RW": { CD: true, DVD: false, "Blu-ray": false },
    "DVD-ROM": { CD: true, DVD: true, "Blu-ray": false },
    "DVD-RW": { CD: true, DVD: true, "Blu-ray": false },
    "Blu-ray ROM": { CD: true, DVD: true, "Blu-ray": true },
    "Blu-ray RW": { CD: true, DVD: true, "Blu-ray": true }
  };

  // ===== Función: Mostrar u ocultar campos de unidad óptica =====
  function toggleUnidadOptica() {
    const tipoComponenteSelect = document.querySelector(
      'select[name="tipo_componente"]' // Select del tipo de componente general
    );
    if (
      tipoComponenteSelect &&
      tipoComponenteSelect.value === "Unidad Optica" // Solo mostrar si el componente seleccionado es "Unidad Optica"
    ) {
      unidadOpticaDiv.style.display = "block";
    } else {
      unidadOpticaDiv.style.display = "none";
    }
  }

  // ===== Función: Marcar checkboxes de discos según compatibilidad =====
  function marcarDiscosCompatibles() {
    const tipoUnidad = tipoSelect.value; // Obtener el tipo de unidad seleccionada
    const discosCompatibles = compatibilidad[tipoUnidad] || {}; // Obtener compatibilidad, o vacío si no existe

    checkboxes.forEach(cb => {
      if (discosCompatibles[cb.value]) {
        cb.checked = true; // Compatible → marcado
        cb.disabled = false; // Habilitado
      } else {
        cb.checked = false; // No compatible → desmarcado
        cb.disabled = true; // Deshabilitado
      }
    });
  }

  // ===== Eventos =====
  // Al cambiar el tipo de unidad, actualizar checkboxes de compatibilidad
  tipoSelect.addEventListener("change", marcarDiscosCompatibles);

  // Al cambiar el tipo de componente general, mostrar u ocultar campos de unidad óptica
  document
    .querySelector('select[name="tipo_componente"]')
    .addEventListener("change", toggleUnidadOptica);

  // ===== Inicialización al cargar la página =====
  toggleUnidadOptica(); // Mostrar u ocultar campos según componente actual
  marcarDiscosCompatibles(); // Marcar discos compatibles según unidad actual
});
