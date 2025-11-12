document.addEventListener("DOMContentLoaded", function() {
  const tipoSelect = document.getElementById("tipo_componente");
  if (!tipoSelect) return;

  const componentesUnicos = [
    "Tarjeta Madre",
    "Procesador",
    "Fuente de Poder",
    "Tarjeta Grafica",
    "Tarjeta Red",
    "Tarjeta de Sonido Integrada"
  ];

  function deshabilitarOpciones(existentes) {
    const actual = tipoSelect.value;
    Array.from(tipoSelect.options).forEach(option => {
      option.disabled =
        componentesUnicos.includes(option.value) &&
        existentes.includes(option.value) &&
        option.value !== actual;
    });
  }

  // 🔹 Caso createPorEquipo
  const equipoHidden = document.getElementById("id_equipo_hidden");
  if (equipoHidden) {
    const existentes = JSON.parse(equipoHidden.dataset.existentes || "[]");
    deshabilitarOpciones(existentes);
    return;
  }

  // 🔹 Caso create normal
  const equipoSelect = document.getElementById("id_equipo");
  if (!equipoSelect) return;

  equipoSelect.addEventListener("change", function() {
    const idEquipo = this.value;
    if (!idEquipo) {
      Array.from(tipoSelect.options).forEach(
        option => (option.disabled = false)
      );
      return;
    }

    fetch(`/componentes/unicos/${idEquipo}`)
      .then(res => res.json())
      .then(data => deshabilitarOpciones(data))
      .catch(err => console.error(err));
  });
});
