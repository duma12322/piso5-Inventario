// ======================================================
// Script: Deshabilitar opciones únicas de componentes
// ======================================================

document.addEventListener("DOMContentLoaded", function() {
  // ===== Obtener select principal =====
  const tipoSelect = document.getElementById("tipo_componente");
  if (!tipoSelect) return; // Si no existe el select, salir

  // ===== Lista de componentes que solo deben existir una vez por equipo =====
  const componentesUnicos = [
    "Tarjeta Madre",
    "Procesador",
    "Fuente de Poder",
    "Tarjeta Grafica",
    "Tarjeta Red",
    "Tarjeta de Sonido Integrada"
  ];

  // ===== Función para deshabilitar opciones que ya existen =====
  // existentes: array de componentes que ya están asociados al equipo
  function deshabilitarOpciones(existentes) {
    const actual = tipoSelect.value; // valor actualmente seleccionado
    Array.from(tipoSelect.options).forEach(option => {
      option.disabled =
        componentesUnicos.includes(option.value) && // solo los únicos
        existentes.includes(option.value) && // si ya existen
        option.value !== actual; // excepto el seleccionado
    });
  }

  // ===== Caso 1: formulario createPorEquipo (equipo ya definido) =====
  const equipoHidden = document.getElementById("id_equipo_hidden");
  if (equipoHidden) {
    const existentes = JSON.parse(equipoHidden.dataset.existentes || "[]");
    deshabilitarOpciones(existentes);
    return; // ya no hace falta el resto del script
  }

  // ===== Caso 2: formulario create normal (selección de equipo dinámica) =====
  const equipoSelect = document.getElementById("id_equipo");
  if (!equipoSelect) return; // si no existe, salir

  // Al cambiar el equipo seleccionado, deshabilitar opciones según los componentes ya asociados
  equipoSelect.addEventListener("change", function() {
    const idEquipo = this.value;

    // Si no hay equipo seleccionado, habilitar todas las opciones
    if (!idEquipo) {
      Array.from(tipoSelect.options).forEach(
        option => (option.disabled = false)
      );
      return;
    }

    // Llamada al servidor para obtener componentes únicos ya existentes
    fetch(`/componentes/unicos/${idEquipo}`)
      .then(res => res.json()) // se espera un array con los nombres de los componentes existentes
      .then(data => deshabilitarOpciones(data)) // deshabilita los que ya existen
      .catch(err => console.error(err)); // manejar errores de fetch
  });
});
