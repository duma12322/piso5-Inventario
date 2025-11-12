document
  .getElementById("toggleOpcionales")
  .addEventListener("click", function() {
    const opcionalesDiv = document.getElementById("opcionales");
    if (opcionalesDiv.style.display === "none") {
      opcionalesDiv.style.display = "block";
      this.textContent = "Ocultar Componentes Opcionales";
    } else {
      opcionalesDiv.style.display = "none";
      this.textContent = "Permitir ver Componentes Opcionales";
    }
  });
