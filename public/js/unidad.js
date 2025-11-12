document.addEventListener("DOMContentLoaded", function() {
  const tipoSelect = document.querySelector('select[name="tipo_unidad"]');
  const unidadOpticaDiv = document.getElementById("unidad_optica_campos");
  const checkboxes = document.querySelectorAll('input[name="tipos_discos[]"]');

  // Tabla de compatibilidad
  const compatibilidad = {
    "CD-ROM": { CD: true, DVD: false, "Blu-ray": false },
    "CD-RW": { CD: true, DVD: false, "Blu-ray": false },
    "DVD-ROM": { CD: true, DVD: true, "Blu-ray": false },
    "DVD-RW": { CD: true, DVD: true, "Blu-ray": false },
    "Blu-ray ROM": { CD: true, DVD: true, "Blu-ray": true },
    "Blu-ray RW": { CD: true, DVD: true, "Blu-ray": true }
  };

  function toggleUnidadOptica() {
    const tipoComponenteSelect = document.querySelector(
      'select[name="tipo_componente"]'
    );
    if (
      tipoComponenteSelect &&
      tipoComponenteSelect.value === "Unidad Optica"
    ) {
      unidadOpticaDiv.style.display = "block";
    } else {
      unidadOpticaDiv.style.display = "none";
    }
  }

  function marcarDiscosCompatibles() {
    const tipoUnidad = tipoSelect.value;
    const discosCompatibles = compatibilidad[tipoUnidad] || {};

    checkboxes.forEach(cb => {
      if (discosCompatibles[cb.value]) {
        cb.checked = true;
        cb.disabled = false; // Compatible → habilitado
      } else {
        cb.checked = false;
        cb.disabled = true; // No compatible → deshabilitado
      }
    });
  }

  // Eventos
  tipoSelect.addEventListener("change", marcarDiscosCompatibles);
  document
    .querySelector('select[name="tipo_componente"]')
    .addEventListener("change", toggleUnidadOptica);

  // Inicializar al cargar la página
  toggleUnidadOptica();
  marcarDiscosCompatibles();
});
