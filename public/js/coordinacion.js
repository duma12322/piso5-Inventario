// ======================================================
// coordinacion.js
// Script para actualizar el select "division" según
// la opción seleccionada en el select "direccion"
// ======================================================

document.addEventListener("DOMContentLoaded", function() {
  // Obtener referencia al select de direcciones
  var direccionSelect = document.getElementById("direccion");

  // Obtener referencia al select de divisiones
  var divisionSelect = document.getElementById("division");

  // ======================================================
  // Evento: cambio en el select de direcciones
  // ======================================================
  direccionSelect.addEventListener("change", function() {
    var id = this.value; // Captura el ID de la dirección seleccionada

    // Si hay un ID válido
    if (id) {
      // Realiza una petición fetch al servidor para obtener las divisiones
      fetch("/coordinaciones/divisiones/" + id)
        .then(response => response.text()) // Convertir la respuesta a texto
        .then(data => {
          // Insertar el contenido devuelto (opciones <option>) en el select de divisiones
          divisionSelect.innerHTML = data;
        })
        .catch(error => console.error("Error:", error)); // Manejo de errores
    } else {
      // Si no hay ID seleccionado, poner opción por defecto
      divisionSelect.innerHTML = '<option value="">Seleccione</option>';
    }
  });
});
