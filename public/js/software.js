document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("add-software").addEventListener("click", () => {
    const container = document.getElementById("otros-softwares");
    const row = document.createElement("div");
    row.classList.add("d-flex", "mb-1");

    row.innerHTML = `
            <input type="text" name="software_nombre_extra[]" class="form-control mr-2" placeholder="Nombre">
            <input type="text" name="software_version_extra[]" class="form-control" placeholder="Versión">
            <button type="button" class="btn btn-danger btn-sm ml-2 remove-software">Eliminar</button>
        `;
    container.appendChild(row);
  });

  document.addEventListener("click", e => {
    if (e.target.classList.contains("remove-software")) {
      e.target.closest("div.d-flex").remove();
    }
  });
});
