// ======================================================
// Script: Agregar y eliminar campos dinámicos de software
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
  // ===== Agregar nuevo software =====
  document.getElementById("add-software").addEventListener("click", () => {
    // Contenedor donde se agregarán los campos extra
    const container = document.getElementById("otros-softwares");

    // Crear un div que contendrá los inputs y botón
    const row = document.createElement("div");
    row.classList.add("d-flex", "mb-1"); // clases de Bootstrap para flex y margen inferior

    // Agregar los campos de nombre, versión y el botón eliminar
    row.innerHTML = `
            <input type="text" name="software_nombre_extra[]" class="form-control mr-2" placeholder="Nombre">
            <input type="text" name="software_version_extra[]" class="form-control" placeholder="Versión">
            <button type="button" class="btn btn-danger btn-sm ml-2 remove-software">Eliminar</button>
        `;

    // Añadir el nuevo div al contenedor
    container.appendChild(row);
  });

  // ===== Eliminar software agregado =====
  document.addEventListener("click", e => {
    // Verifica si el elemento clickeado tiene la clase "remove-software"
    if (e.target.classList.contains("remove-software")) {
      // Remueve el div contenedor más cercano de ese botón
      e.target.closest("div.d-flex").remove();
    }
  });
});
