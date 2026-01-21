// ======================================================
// Script: Manejo dinámico de selects de Dirección, División y Coordinación
// Dependencias jerárquicas entre los selects
// ======================================================

document.addEventListener("DOMContentLoaded", () => {
    // ===== Lectura de datos desde data-attributes =====
    const appData = document.getElementById("app-data");

    // Parsear los JSON de direcciones, divisiones y coordinaciones
    const direcciones = JSON.parse(appData.dataset.direcciones);
    const divisiones = JSON.parse(appData.dataset.divisiones);
    const coordinaciones = JSON.parse(appData.dataset.coordinaciones);

    // ===== Referencias a los selects del formulario =====
    const direccionSelect = document.getElementById("id_direccion");
    const divisionSelect = document.getElementById("id_division");
    const coordinacionSelect = document.getElementById("id_coordinacion");

    // ===== Función: Actualiza las divisiones según la dirección seleccionada =====
    const actualizarDivisiones = (idDireccion, selectedDivision = null) => {
        // Limpiar el select de divisiones y reiniciar el de coordinaciones
        divisionSelect.innerHTML = '<option value="">Todas</option>';
        coordinacionSelect.innerHTML = '<option value="">Todas</option>';
        coordinacionSelect.disabled = true; // deshabilitar coordinaciones hasta elegir división

        // Si no hay dirección seleccionada, deshabilitar select
        if (!idDireccion) {
            divisionSelect.disabled = true;
            return;
        }

        // Filtrar divisiones que pertenecen a la dirección seleccionada
        const filtradas = divisiones.filter(d => d.id_direccion == idDireccion);

        // Agregar cada división como opción al select
        filtradas.forEach(d => {
            const opt = document.createElement("option");
            opt.value = d.id_division;
            // Soporte para ambos posibles nombres: nombre_division o nombre
            opt.textContent = d.nombre_division ?? d.nombre;
            // Seleccionar opción si coincide con selectedDivision
            if (selectedDivision && selectedDivision == d.id_division) opt.selected = true;
            divisionSelect.appendChild(opt);
        });

        divisionSelect.disabled = false; // habilitar select
    };

    // ===== Función: Actualiza las coordinaciones según la división seleccionada =====
    const actualizarCoordinaciones = (idDivision, selectedCoord = null) => {
        // Limpiar select de coordinaciones
        coordinacionSelect.innerHTML = '<option value="">Todas</option>';

        // Si no hay división seleccionada, deshabilitar select
        if (!idDivision) {
            coordinacionSelect.disabled = true;
            return;
        }

        // Filtrar coordinaciones que pertenecen a la división seleccionada
        const filtradas = coordinaciones.filter(c => c.id_division == idDivision);

        // Agregar cada coordinación como opción al select
        filtradas.forEach(c => {
            const opt = document.createElement("option");
            // Soporte para ambos posibles ids: id_coordinacion o id
            opt.value = c.id_coordinacion ?? c.id;
            // Soporte para ambos posibles nombres: nombre_coordinacion o nombre
            opt.textContent = c.nombre_coordinacion ?? c.nombre;
            // Seleccionar opción si coincide con selectedCoord
            if (selectedCoord && selectedCoord == (c.id_coordinacion ?? c.id)) opt.selected = true;
            coordinacionSelect.appendChild(opt);
        });

        coordinacionSelect.disabled = false; // habilitar select
    };

    // ===== Eventos de cambio de selects =====

    // Cambio de dirección: actualizar divisiones y reiniciar coordinaciones
    direccionSelect.addEventListener("change", () => {
        const idDireccion = parseInt(direccionSelect.value);
        actualizarDivisiones(idDireccion);
        coordinacionSelect.value = '';
        coordinacionSelect.disabled = true;
    });

    // Cambio de división: actualizar coordinaciones y ajustar dirección automáticamente
    divisionSelect.addEventListener("change", () => {
        const idDivision = parseInt(divisionSelect.value);
        actualizarCoordinaciones(idDivision);
        coordinacionSelect.value = '';

        // Ajustar select de dirección para que coincida con la división seleccionada
        const divObj = divisiones.find(d => d.id_division == idDivision);
        if (divObj) direccionSelect.value = divObj.id_direccion;
    });

    // Cambio de coordinación: ajustar división y dirección automáticamente
    coordinacionSelect.addEventListener("change", () => {
        const coordId = parseInt(coordinacionSelect.value);
        const coordObj = coordinaciones.find(c => (c.id_coordinacion ?? c.id) == coordId);
        if (coordObj) {
            divisionSelect.value = coordObj.id_division;
            const divObj = divisiones.find(d => d.id_division == coordObj.id_division);
            if (divObj) direccionSelect.value = divObj.id_direccion;
        }
    });

    // ===== Inicializar selects según valores preseleccionados (dataset o value) =====
    const selectedDireccion = parseInt(direccionSelect.dataset.selected) || parseInt(direccionSelect.value);
    const selectedDivision = parseInt(divisionSelect.dataset.selected) || parseInt(divisionSelect.value);
    const selectedCoordinacion = parseInt(coordinacionSelect.dataset.selected) || parseInt(coordinacionSelect.value);

    if (selectedDireccion) actualizarDivisiones(selectedDireccion, selectedDivision);
    if (selectedDivision) actualizarCoordinaciones(selectedDivision, selectedCoordinacion);
});
