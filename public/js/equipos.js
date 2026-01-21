// ======================================================
// Script: Dinámicas de selección de direcciones, divisiones,
// coordinaciones, niveles de equipo y software adicional
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
  // Obtener el contenedor que tiene los datos como atributos data-*
  const appData = document.getElementById("app-data");

  // Parsear los datos JSON desde los data-attributes
  const direcciones = JSON.parse(appData.dataset.direcciones);
  const divisiones = JSON.parse(appData.dataset.divisiones);
  const coordinaciones = JSON.parse(appData.dataset.coordinaciones);
  const tiposSoftware = JSON.parse(appData.dataset.tiposSoftware);
  const softwareActual = JSON.parse(appData.dataset.softwareActual);

  // ===== Obtener referencias a los selects principales =====
  const direccionSelect = document.getElementById("direccion");
  const divisionSelect = document.getElementById("division");
  const coordinacionSelect = document.getElementById("coordinacion");
  const nivelSelect = document.getElementById("nivel-equipo");
  const niveles = document.querySelectorAll(".nivel");

  // ===== Mostrar u ocultar niveles según la selección =====
  if (nivelSelect) {
    const mostrarNivel = () => {
      const nivel = nivelSelect.value;

      // Ocultar todos los niveles
      niveles.forEach(n => (n.style.display = "none"));

      // Mostrar solo los elementos que correspondan al nivel seleccionado
      if (nivel) {
        document
          .querySelectorAll(`.${nivel}`)
          .forEach(el => (el.style.display = "block"));
      }
    };

    // Ejecutar función al cambiar selección
    nivelSelect.addEventListener("change", mostrarNivel);

    // Ejecutar inicialmente para mostrar el nivel preseleccionado
    mostrarNivel();
  }

  // ===== Gestión dinámica de software adicional =====
  const addBtn = document.getElementById("add-software");
  const otrosSoftwares = document.getElementById("otros-softwares");

  if (addBtn && otrosSoftwares) {
    addBtn.addEventListener("click", () => {
      // Crear un nuevo div que contendrá inputs de nombre y versión
      const div = document.createElement("div");
      div.className = "d-flex mb-1";

      // HTML interno con inputs y botón de eliminación
      div.innerHTML = `
        <input type="text" name="software_nombre_extra[]" class="form-control mr-2" placeholder="Nombre">
        <input type="text" name="software_version_extra[]" class="form-control" placeholder="Versión">
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-software">Eliminar</button>
      `;

      // Agregar al contenedor de otros softwares
      otrosSoftwares.appendChild(div);

      // Asignar evento al botón eliminar para remover este bloque
      div
        .querySelector(".remove-software")
        .addEventListener("click", () => div.remove());
    });
  }

  // ===== Actualizar dirección automáticamente al cambiar división =====
  if (divisionSelect) {
    divisionSelect.addEventListener("change", () => {
      const divisionId = parseInt(divisionSelect.value);

      // Buscar el objeto de división seleccionado
      const divObj = divisiones.find(d => d.id_division === divisionId);

      // Actualizar el select de dirección según la división seleccionada
      direccionSelect.value = divObj ? divObj.id_direccion : "";
    });
  }

  // ===== Actualizar división y dirección al cambiar coordinación =====
  if (coordinacionSelect) {
    coordinacionSelect.addEventListener("change", () => {
      const coordId = parseInt(coordinacionSelect.value);

      // Buscar la coordinación seleccionada
      const coordObj = coordinaciones.find(c => c.id_coordinacion === coordId);

      if (coordObj) {
        // Actualizar los selects de división y dirección
        // Se asume que cada coordinación tiene id_division y id_direccion
        divisionSelect.value = coordObj.id_division || "";
        direccionSelect.value = coordObj.id_direccion || "";
      } else {
        // Si no hay coordinación seleccionada, limpiar selects
        divisionSelect.value = "";
        direccionSelect.value = "";
      }
    });
  }
});
