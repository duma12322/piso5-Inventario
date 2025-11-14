document.addEventListener("DOMContentLoaded", () => {
    const appData = document.getElementById("app-data");
    const direcciones = JSON.parse(appData.dataset.direcciones);
    const divisiones = JSON.parse(appData.dataset.divisiones);
    const coordinaciones = JSON.parse(appData.dataset.coordinaciones);

    const direccionSelect = document.getElementById("id_direccion");
    const divisionSelect = document.getElementById("id_division");
    const coordinacionSelect = document.getElementById("id_coordinacion");

    // Función para llenar select de divisiones según dirección
    const actualizarDivisiones = (idDireccion) => {
        divisionSelect.innerHTML = '<option value="">Seleccione</option>';
        coordinacionSelect.innerHTML = '<option value="">Todas</option>';
        coordinacionSelect.disabled = true;

        if (!idDireccion) {
            divisionSelect.disabled = true;
            return;
        }

        const filtradas = divisiones.filter(d => d.id_direccion === idDireccion);
        filtradas.forEach(d => {
            const opt = document.createElement("option");
            opt.value = d.id_division;
            opt.textContent = d.nombre_division ?? d.nombre;
            divisionSelect.appendChild(opt);
        });
        divisionSelect.disabled = false;
    };

    // Función para llenar select de coordinaciones según división
    const actualizarCoordinaciones = (idDivision) => {
        coordinacionSelect.innerHTML = '<option value="">Seleccione</option>';

        if (!idDivision) {
            coordinacionSelect.disabled = true;
            return;
        }

        const filtradas = coordinaciones.filter(c => c.id_division === idDivision);
        filtradas.forEach(c => {
            const opt = document.createElement("option");
            opt.value = c.id_coordinacion ?? c.id;
            opt.textContent = c.nombre_coordinacion ?? c.nombre;
            coordinacionSelect.appendChild(opt);
        });
        coordinacionSelect.disabled = false;
    };

    // ===== Eventos =====
    direccionSelect.addEventListener("change", () => {
        const idDireccion = parseInt(direccionSelect.value);
        actualizarDivisiones(idDireccion);
    });

    divisionSelect.addEventListener("change", () => {
        const idDivision = parseInt(divisionSelect.value);
        actualizarCoordinaciones(idDivision);

        // Mantener la dirección correcta
        const divObj = divisiones.find(d => d.id_division === idDivision);
        if (divObj) direccionSelect.value = divObj.id_direccion;
    });

    coordinacionSelect.addEventListener("change", () => {
        const coordId = parseInt(coordinacionSelect.value);
        const coordObj = coordinaciones.find(c => (c.id_coordinacion ?? c.id) === coordId);
        if (coordObj) {
            divisionSelect.value = coordObj.id_division;
            const divObj = divisiones.find(d => d.id_division === coordObj.id_division);
            if (divObj) direccionSelect.value = divObj.id_direccion;
        }
    });

    // ===== Inicializar selects si hay filtros aplicados =====
    const selectedDireccion = parseInt(direccionSelect.value);
    const selectedDivision = parseInt(divisionSelect.value);
    if (selectedDireccion) actualizarDivisiones(selectedDireccion);
    if (selectedDivision) actualizarCoordinaciones(selectedDivision);
});
