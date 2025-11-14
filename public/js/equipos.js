document.addEventListener("DOMContentLoaded", () => {
  // Leer datos desde data-attributes
  const appData = document.getElementById("app-data");

  const direcciones = JSON.parse(appData.dataset.direcciones);
  const divisiones = JSON.parse(appData.dataset.divisiones);
  const coordinaciones = JSON.parse(appData.dataset.coordinaciones);
  const tiposSoftware = JSON.parse(appData.dataset.tiposSoftware);
  const softwareActual = JSON.parse(appData.dataset.softwareActual);

  // Selects
  const direccionSelect = document.getElementById("direccion");
  const divisionSelect = document.getElementById("division");
  const coordinacionSelect = document.getElementById("coordinacion");
  const nivelSelect = document.getElementById("nivel-equipo");
  const niveles = document.querySelectorAll(".nivel");

  // ===== Mostrar niveles =====
  if (nivelSelect) {
    const mostrarNivel = () => {
      const nivel = nivelSelect.value;
      niveles.forEach(n => (n.style.display = "none"));
      if (nivel) {
        document
          .querySelectorAll(`.${nivel}`)
          .forEach(el => (el.style.display = "block"));
      }
    };
    nivelSelect.addEventListener("change", mostrarNivel);
    mostrarNivel();
  }

  // ===== Software dinámico =====
  const addBtn = document.getElementById("add-software");
  const otrosSoftwares = document.getElementById("otros-softwares");

  if (addBtn && otrosSoftwares) {
    addBtn.addEventListener("click", () => {
      const div = document.createElement("div");
      div.className = "d-flex mb-1";
      div.innerHTML = `
        <input type="text" name="software_nombre_extra[]" class="form-control mr-2" placeholder="Nombre">
        <input type="text" name="software_version_extra[]" class="form-control" placeholder="Versión">
        <button type="button" class="btn btn-danger btn-sm ml-2 remove-software">Eliminar</button>
      `;
      otrosSoftwares.appendChild(div);
      div
        .querySelector(".remove-software")
        .addEventListener("click", () => div.remove());
    });
  }

  // ===== Actualizar dirección cuando se selecciona división =====
  if (divisionSelect) {
    divisionSelect.addEventListener("change", () => {
      const divisionId = parseInt(divisionSelect.value);
      const divObj = divisiones.find(d => d.id_division === divisionId);
      direccionSelect.value = divObj ? divObj.id_direccion : "";
    });
  }

  // ===== Actualizar división y dirección cuando se selecciona coordinación =====
  if (coordinacionSelect) {
    coordinacionSelect.addEventListener("change", () => {
      const coordId = parseInt(coordinacionSelect.value);
      const coordObj = coordinaciones.find(c => c.id_coordinacion === coordId);

      if (coordObj) {
        // Para que funcione, la estructura de coordinaciones debe incluir id_division y id_direccion
        divisionSelect.value = coordObj.id_division || "";
        direccionSelect.value = coordObj.id_direccion || "";
      } else {
        divisionSelect.value = "";
        direccionSelect.value = "";
      }
    });
  }
});
