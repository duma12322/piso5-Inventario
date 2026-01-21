// ======================================================
// Script: Toggle de visibilidad del div "opcionales"
// ======================================================

document
  .getElementById("toggleOpcionales") // Selecciona el botón con id "toggleOpcionales"
  .addEventListener("click", function() {
    // Selecciona el div que contiene los componentes opcionales
    const opcionalesDiv = document.getElementById("opcionales");

    // Comprueba si el div está oculto
    if (opcionalesDiv.style.display === "none") {
      // Si estaba oculto, mostrarlo
      opcionalesDiv.style.display = "block";
      // Cambiar el texto del botón para indicar que se puede ocultar
      this.textContent = "Ocultar Componentes Opcionales";
    } else {
      // Si estaba visible, ocultarlo
      opcionalesDiv.style.display = "none";
      // Cambiar el texto del botón para indicar que se puede mostrar
      this.textContent = "Permitir ver Componentes Opcionales";
    }
  });
