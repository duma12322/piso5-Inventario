// ======================================================
// Script: Gestión de formulario de dirección, división y coordinación
// Incluye toggles de componentes, expansión/colapso y animación de filas
// ======================================================

document.addEventListener("DOMContentLoaded", function() {
  // ===== Inicializar la aplicación al cargar la página =====
  initApp();

  function initApp() {
    // Elementos del DOM
    const direccionSelect = document.getElementById("id_direccion");
    const divisionSelect = document.getElementById("id_division");
    const coordinacionSelect = document.getElementById("id_coordinacion");
    const toggleButtons = document.querySelectorAll(".toggle-componentes");
    const expandAllBtn = document.getElementById("expand-all");
    const collapseAllBtn = document.getElementById("collapse-all");
    const filterForm = document.getElementById("filter-form");

    // Verificar que los elementos existan
    if (!direccionSelect || !divisionSelect || !coordinacionSelect) {
      console.error("Elementos del formulario no encontrados");
      return;
    }

    // ===== Cargar datos iniciales desde el contenedor HTML =====
    loadInitialData(direccionSelect, divisionSelect, coordinacionSelect);

    // ===== Configurar eventos de interacción =====
    setupEventListeners(
      direccionSelect,
      divisionSelect,
      coordinacionSelect,
      toggleButtons,
      expandAllBtn,
      collapseAllBtn,
      filterForm
    );

    // ===== Animar filas de la tabla (por ejemplo, fade in) =====
    animateTableRows();
  }

  // ===== Función: Cargar datos iniciales en los selects =====
  function loadInitialData(
    direccionSelect,
    divisionSelect,
    coordinacionSelect
  ) {
    // Obtener contenedor con data-attributes
    const appData = document.getElementById("app-data");
    if (!appData) return;

    let direcciones = [];
    let divisiones = [];
    let coordinaciones = [];

    try {
      // Parsear los datos JSON
      direcciones = JSON.parse(appData.dataset.direcciones || "[]");
      divisiones = JSON.parse(appData.dataset.divisiones || "[]");
      coordinaciones = JSON.parse(appData.dataset.coordinaciones || "[]");
    } catch (error) {
      console.error("Error al parsear datos JSON:", error);
      return;
    }

    // ===== Configurar eventos de cambio =====
    // Cuando cambia la dirección → cargar divisiones correspondientes
    direccionSelect.addEventListener("change", function() {
      cargarDivisiones(this.value, divisiones, divisionSelect);
    });

    // Cuando cambia la división → cargar coordinaciones correspondientes
    divisionSelect.addEventListener("change", function() {
      cargarCoordinaciones(this.value, coordinaciones, coordinacionSelect);
    });

    // ===== Inicializar selects si ya hay una dirección seleccionada =====
    if (direccionSelect.value) {
      cargarDivisiones(direccionSelect.value, divisiones, divisionSelect);
    }
  }

  // ===== Función: Cargar opciones de divisiones según dirección seleccionada =====
  function cargarDivisiones(direccionId, divisiones, divisionSelect) {
    // Limpiar y deshabilitar
    divisionSelect.innerHTML = '<option value="">Todas las divisiones</option>';
    divisionSelect.disabled = true;

    // Limpiar coordinaciones también
    const coordinacionSelect = document.getElementById("id_coordinacion");
    if (coordinacionSelect) {
      coordinacionSelect.innerHTML =
        '<option value="">Todas las coordinaciones</option>';
      coordinacionSelect.disabled = true;
    }

    if (!direccionId) return;

    // Filtrar divisiones
    const divisionesFiltradas = divisiones.filter(
      division => division.id_direccion == direccionId
    );

    // Llenar opciones
    divisionesFiltradas.forEach(division => {
      const option = document.createElement("option");
      option.value = division.id_division;
      option.textContent = division.nombre_division || division.nombre;
      divisionSelect.appendChild(option);
    });

    // Habilitar y restaurar valor
    divisionSelect.disabled = false;
    const selectedDivision = divisionSelect.dataset.selected;
    if (selectedDivision) {
      divisionSelect.value = selectedDivision;

      // Cargar coordinaciones si hay una división seleccionada
      if (coordinacionSelect) {
        cargarCoordinaciones(selectedDivision, [], coordinacionSelect);
      }
    }
  }

  // ===== Función: Cargar opciones de coordinaciones según división seleccionada =====
  function cargarCoordinaciones(
    divisionId,
    coordinaciones,
    coordinacionSelect
  ) {
    // Limpiar y deshabilitar
    coordinacionSelect.innerHTML =
      '<option value="">Todas las coordinaciones</option>';
    coordinacionSelect.disabled = true;

    if (!divisionId) return;

    // Filtrar coordinaciones
    const coordinacionesFiltradas = coordinaciones.filter(
      coordinacion => coordinacion.id_division == divisionId
    );

    // Llenar opciones
    coordinacionesFiltradas.forEach(coordinacion => {
      const option = document.createElement("option");
      option.value = coordinacion.id_coordinacion;
      option.textContent =
        coordinacion.nombre_coordinacion || coordinacion.nombre;
      coordinacionSelect.appendChild(option);
    });

    // Habilitar y restaurar valor
    coordinacionSelect.disabled = false;
    const selectedCoordinacion = coordinacionSelect.dataset.selected;
    if (selectedCoordinacion) {
      coordinacionSelect.value = selectedCoordinacion;
    }
  }

  // ======================================================
  // Funciones de toggle, expand/collapse y animaciones
  // ======================================================

  // ===== Función: Configurar listeners de toggle y botones de expand/collapse =====
  function setupEventListeners(
    direccionSelect, // Select de dirección
    divisionSelect, // Select de división
    coordinacionSelect, // Select de coordinación
    toggleButtons, // Botones para mostrar/ocultar componentes individuales
    expandAllBtn, // Botón para expandir todos los componentes
    collapseAllBtn, // Botón para colapsar todos los componentes
    filterForm // Formulario de filtrado
  ) {
    // ===== Eventos de toggle individual de componentes =====
    if (toggleButtons && toggleButtons.length > 0) {
      toggleButtons.forEach(button => {
        // Al hacer click, llamar a toggleComponentDetails
        button.addEventListener("click", toggleComponentDetails);
      });
    }

    // ===== Evento para expandir todas las filas =====
    if (expandAllBtn) {
      expandAllBtn.addEventListener("click", function() {
        toggleAllComponentDetails("expand");
      });
    }

    // ===== Evento para colapsar todas las filas =====
    if (collapseAllBtn) {
      collapseAllBtn.addEventListener("click", function() {
        toggleAllComponentDetails("collapse");
      });
    }

    // ===== Evento submit del formulario de filtrado =====
    if (filterForm) {
      filterForm.addEventListener("submit", function(e) {
        showLoadingState(e.target); // Muestra spinner en el botón submit
      });
    }
  }

  // ===== Función: Mostrar u ocultar los detalles de un componente =====
  function toggleComponentDetails(event) {
    event.preventDefault(); // Evitar comportamiento por defecto del botón
    const button = event.currentTarget; // Botón que disparó el evento
    const targetId = button.getAttribute("data-target"); // ID del div a mostrar/ocultar
    const targetElement = document.getElementById(targetId); // Elemento objetivo

    if (!targetElement) {
      console.error("Elemento objetivo no encontrado:", targetId);
      return;
    }

    // ===== Toggle de visibilidad =====
    if (
      targetElement.style.display === "none" ||
      !targetElement.style.display
    ) {
      // Mostrar elemento
      targetElement.style.display = "block";
      targetElement.style.opacity = "0"; // Inicio de animación de opacidad
      targetElement.style.transform = "translateY(-10px)"; // Inicio de animación de desplazamiento

      // Animación con transición suave
      requestAnimationFrame(() => {
        targetElement.style.transition =
          "opacity 0.3s ease, transform 0.3s ease";
        targetElement.style.opacity = "1";
        targetElement.style.transform = "translateY(0)";
      });

      // Cambiar ícono del botón si existe
      const icon = button.querySelector("i");
      if (icon) {
        icon.classList.remove("fa-chevron-down");
        icon.classList.add("fa-chevron-up");
      }

      button.classList.add("active"); // Añadir clase active al botón
    } else {
      // Ocultar elemento
      targetElement.style.opacity = "0";
      targetElement.style.transform = "translateY(-10px)";

      // Esperar transición antes de ocultar completamente
      setTimeout(() => {
        targetElement.style.display = "none";
      }, 300);

      // Cambiar ícono del botón si existe
      const icon = button.querySelector("i");
      if (icon) {
        icon.classList.remove("fa-chevron-up");
        icon.classList.add("fa-chevron-down");
      }

      button.classList.remove("active"); // Remover clase active del botón
    }
  }

  // ===== Función: Expandir o colapsar todos los componentes =====
  function toggleAllComponentDetails(action) {
    const toggleButtons = document.querySelectorAll(".toggle-componentes");

    toggleButtons.forEach(button => {
      const targetId = button.getAttribute("data-target");
      const targetElement = document.getElementById(targetId);

      if (!targetElement) return;

      if (action === "expand") {
        // Expandir solo si está oculto
        if (
          targetElement.style.display === "none" ||
          !targetElement.style.display
        ) {
          button.click(); // Reutiliza toggleComponentDetails
        }
      } else if (action === "collapse") {
        // Colapsar solo si está visible
        if (targetElement.style.display === "block") {
          button.click(); // Reutiliza toggleComponentDetails
        }
      }
    });
  }

  // ===== Función: Mostrar estado de carga en el botón submit =====
  function showLoadingState(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
      const originalHTML = submitButton.innerHTML;
      // Cambiar contenido a spinner
      submitButton.innerHTML =
        '<i class="fas fa-spinner fa-spin me-2"></i>Filtrando...';
      submitButton.disabled = true;

      // Restaurar después de 3 segundos máximo
      setTimeout(() => {
        submitButton.innerHTML = originalHTML;
        submitButton.disabled = false;
      }, 3000);
    }
  }

  // ===== Función: Animar filas de la tabla al cargar =====
  function animateTableRows() {
    const rows = document.querySelectorAll(".equipo-row");
    rows.forEach((row, index) => {
      // Estado inicial invisible y desplazado
      row.style.opacity = "0";
      row.style.transform = "translateY(20px)";

      // Animación con delay según índice
      setTimeout(() => {
        row.style.transition = "opacity 0.5s ease, transform 0.5s ease";
        row.style.opacity = "1";
        row.style.transform = "translateY(0)";
      }, index * 100); // Cada fila se anima con 100ms de diferencia
    });
  }
});
