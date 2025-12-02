document.addEventListener("DOMContentLoaded", () => {
  const tipoSelect = document.getElementById("tipo_componente");

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

  const mostrarCampos = tipo => {
    // Si no hay tipo seleccionado, ocultar todo y salir
    if (!tipo) {
      secciones.forEach(id => {
        const elem = document.getElementById(id);
        if (elem) elem.style.display = "none";
      });
      return;
    }

    // Ocultar todo
    secciones.forEach(id => {
      const elem = document.getElementById(id);
      if (elem) elem.style.display = "none";
    });

    const tipoNormalizado = tipo.toLowerCase();

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

  // Ejecutar al cargar (solo si hay valor)
  if (tipoSelect.value) {
    mostrarCampos(tipoSelect.value);
  }

  // Ejecutar cuando cambie
  tipoSelect.addEventListener("change", e => mostrarCampos(e.target.value));

  console.log("JS unificado cargado");
});
