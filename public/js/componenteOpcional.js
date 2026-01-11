function mostrarCampos(tipo) {
  const secciones = [
    "tarjeta_madre_campos",
    "memoria_ram_campos",
    "tarjeta_wifi_campos",
    "disco_duro_campos",
    "tarjeta_grafica_campos",
    "tarjeta_red_campos",
    "fan_cooler_campos",
    "tarjeta_sonido_campos",
  ];

  secciones.forEach(id => {
    const elem = document.getElementById(id);
    if (elem) elem.style.display = "none";
  });

  const tipoNormalizado = tipo.toLowerCase();

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

document.addEventListener("DOMContentLoaded", () => {
  const tipoSeleccionado = document.getElementById("tipo_opcional").value;
  mostrarCampos(tipoSeleccionado);

  document.getElementById("tipo_opcional").addEventListener("change", e => {
    mostrarCampos(e.target.value);
  });
});
