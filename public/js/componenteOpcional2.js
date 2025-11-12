const tipo = document.getElementById("tipo_opcional");
const ram_campos = document.getElementById("ram_campos");
// ... otros campos

function mostrarCampos() {
  ram_campos.style.display = "none";
  // ... ocultar otros

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

tipo.addEventListener("change", mostrarCampos);
mostrarCampos(); // inicializar
