// ======================================================
// Selección de elementos del DOM
// ======================================================

// Obtener el select que determina el tipo de componente
const tipo = document.getElementById("tipo_opcional");

// Obtener los contenedores de campos de cada componente
const ram_campos = document.getElementById("ram_campos");
const disco_duro_campos = document.getElementById("disco_duro_campos");
const fan_cooler_campos = document.getElementById("fan_cooler_campos");
const tarjeta_grafica_campos = document.getElementById(
  "tarjeta_grafica_campos"
);
const tarjeta_red_campos = document.getElementById("tarjeta_red_campos");
const tarjeta_wifi_campos = document.getElementById("tarjeta_wifi_campos");
const tarjeta_sonido_campos = document.getElementById("tarjeta_sonido_campos");

// ======================================================
// Función para mostrar/ocultar campos según el tipo seleccionado
// ======================================================
function mostrarCampos() {
  // Ocultar todos los campos antes de mostrar el correcto
  ram_campos.style.display = "none";
  disco_duro_campos.style.display = "none";
  fan_cooler_campos.style.display = "none";
  tarjeta_grafica_campos.style.display = "none";
  tarjeta_red_campos.style.display = "none";
  tarjeta_wifi_campos.style.display = "none";
  tarjeta_sonido_campos.style.display = "none";

  // Mostrar solo la sección correspondiente según el valor del select
  switch (tipo.value) {
    case "Memoria Ram":
      ram_campos.style.display = "block";
      break;
    case "Disco Duro":
      disco_duro_campos.style.display = "block";
      break;
    case "Fan Cooler":
      fan_cooler_campos.style.display = "block";
      break;
    case "Tarjeta Grafica":
      tarjeta_grafica_campos.style.display = "block";
      break;
    case "Tarjeta de Red":
      tarjeta_red_campos.style.display = "block";
      break;
    case "Tarjeta WiFi":
      tarjeta_wifi_campos.style.display = "block";
      break;
    case "Tarjeta de Sonido":
      tarjeta_sonido_campos.style.display = "block";
      break;
  }
}

// ======================================================
// Eventos
// ======================================================

// Ejecutar la función cada vez que cambie el select
tipo.addEventListener("change", mostrarCampos);

// Ejecutar la función al cargar la página para mostrar el campo inicial
mostrarCampos();
