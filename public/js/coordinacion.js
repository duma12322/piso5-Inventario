document.addEventListener("DOMContentLoaded", function() {
  var direccionSelect = document.getElementById("direccion");
  var divisionSelect = document.getElementById("division");

  direccionSelect.addEventListener("change", function() {
    var id = this.value;
    if (id) {
      fetch("/coordinaciones/divisiones/" + id)
        .then(response => response.text())
        .then(data => {
          divisionSelect.innerHTML = data; // data ya viene en formato <option>
        })
        .catch(error => console.error("Error:", error));
    } else {
      divisionSelect.innerHTML = '<option value="">Seleccione</option>';
    }
  });
});
