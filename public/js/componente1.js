// ======================================================
// Mostrar/Ocultar campos según el tipo de componente
// ======================================================

// Ejecutar cuando el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  // Selecciona el <select> con los tipos de componentes
  const tipoSelect = document.getElementById("tipo_componente");

  // Array con los IDs de todas las secciones posibles
  const secciones = [
    "tarjeta_madre_campos",
    "memoria_ram_campos",
    "fuente_poder_campos",
    "disco_duro_campos",
    "tarjeta_grafica_campos",
    "tarjeta_red_campos",
    "unidad_optica_campos",
    "fan_cooler_campos",
    "procesador_campos"
  ];

  // Función para mostrar solo los campos correspondientes al tipo seleccionado
  const mostrarCampos = tipo => {
    // Si no hay tipo seleccionado, ocultar todas las secciones y salir
    if (!tipo) {
      secciones.forEach(id => {
        const elem = document.getElementById(id);
        if (elem) elem.style.display = "none"; // Oculta cada sección
      });
      return;
    }

    // Primero, ocultar todas las secciones
    secciones.forEach(id => {
      const elem = document.getElementById(id);
      if (elem) elem.style.display = "none";
    });

    // Normalizar el texto del tipo a minúsculas para comparaciones
    const tipoNormalizado = tipo.toLowerCase();

    // Mostrar solo la sección correspondiente
    if (tipoNormalizado.includes("madre")) {
      document.getElementById("tarjeta_madre_campos").style.display = "block";
    } else if (tipoNormalizado.includes("ram")) {
      document.getElementById("memoria_ram_campos").style.display = "block";
    } else if (tipoNormalizado.includes("fuente")) {
      document.getElementById("fuente_poder_campos").style.display = "block";
    } else if (tipoNormalizado.includes("disco")) {
      document.getElementById("disco_duro_campos").style.display = "block";
    } else if (tipoNormalizado.includes("grafica")) {
      document.getElementById("tarjeta_grafica_campos").style.display = "block";
    } else if (tipoNormalizado.includes("red")) {
      document.getElementById("tarjeta_red_campos").style.display = "block";
    } else if (tipoNormalizado.includes("optica")) {
      document.getElementById("unidad_optica_campos").style.display = "block";
    } else if (
      tipoNormalizado.includes("fan") ||
      tipoNormalizado.includes("cooler")
    ) {
      document.getElementById("fan_cooler_campos").style.display = "block";
    } else if (tipoNormalizado.includes("procesador")) {
      document.getElementById("procesador_campos").style.display = "block";
    }
  };

  // ======================================================
  // Inicialización al cargar la página
  // Si el select ya tiene un valor, mostrar la sección correspondiente
  // ======================================================
  if (tipoSelect.value) {
    mostrarCampos(tipoSelect.value);
  }

  // ======================================================
  // Escuchar cambios en el select
  // Cada vez que se cambia el tipo, actualizar los campos mostrados
  // ======================================================
  tipoSelect.addEventListener("change", e => mostrarCampos(e.target.value));

  // Mensaje de confirmación en consola
  console.log("JS unificado cargado");
});
