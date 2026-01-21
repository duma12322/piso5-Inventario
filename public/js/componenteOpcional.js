// ======================================================
// Función para mostrar u ocultar secciones según el tipo
// ======================================================
function mostrarCampos(tipo) {
  // Array con los IDs de todas las secciones posibles
  const secciones = [
    "tarjeta_madre_campos",
    "memoria_ram_campos",
    "tarjeta_wifi_campos",
    "disco_duro_campos",
    "tarjeta_grafica_campos",
    "tarjeta_red_campos",
    "fan_cooler_campos",
    "tarjeta_sonido_campos"
  ];

  // Ocultar todas las secciones antes de mostrar la correcta
  secciones.forEach(id => {
    const elem = document.getElementById(id);
    if (elem) elem.style.display = "none"; // Oculta la sección si existe
  });

  // Convertir el tipo a minúsculas para comparaciones seguras
  const tipoNormalizado = tipo.toLowerCase();

  // Mostrar la sección correspondiente según el tipo seleccionado
  if (tipoNormalizado.includes("madre")) {
    document.getElementById("tarjeta_madre_campos").style.display = "block";
  } else if (tipoNormalizado.includes("ram")) {
    document.getElementById("memoria_ram_campos").style.display = "block";
  } else if (tipoNormalizado.includes("wifi")) {
    document.getElementById("tarjeta_wifi_campos").style.display = "block";
  } else if (tipoNormalizado.includes("disco")) {
    document.getElementById("disco_duro_campos").style.display = "block";
  } else if (tipoNormalizado.includes("grafica")) {
    document.getElementById("tarjeta_grafica_campos").style.display = "block";
  } else if (tipoNormalizado.includes("red")) {
    document.getElementById("tarjeta_red_campos").style.display = "block";
  } else if (
    tipoNormalizado.includes("fan") ||
    tipoNormalizado.includes("cooler")
  ) {
    document.getElementById("fan_cooler_campos").style.display = "block";
  } else if (tipoNormalizado.includes("sonido")) {
    document.getElementById("tarjeta_sonido_campos").style.display = "block";
  }
}

// ======================================================
// Código que se ejecuta al cargar la página
// ======================================================
document.addEventListener("DOMContentLoaded", () => {
  // Tomar el valor inicial del select al cargar la página
  const tipoSeleccionado = document.getElementById("tipo_opcional").value;
  mostrarCampos(tipoSeleccionado); // Mostrar la sección correspondiente

  // Escuchar cambios en el select
  document.getElementById("tipo_opcional").addEventListener("change", e => {
    mostrarCampos(e.target.value); // Actualizar la sección al cambiar
  });
});
