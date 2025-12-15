document.addEventListener('DOMContentLoaded', function() {
    // Inicializar la aplicación
    initApp();
    
    function initApp() {
        // Elementos del DOM
        const direccionSelect = document.getElementById('id_direccion');
        const divisionSelect = document.getElementById('id_division');
        const coordinacionSelect = document.getElementById('id_coordinacion');
        const toggleButtons = document.querySelectorAll('.toggle-componentes');
        const expandAllBtn = document.getElementById('expand-all');
        const collapseAllBtn = document.getElementById('collapse-all');
        const filterForm = document.getElementById('filter-form');
        
        // Verificar que los elementos existan
        if (!direccionSelect || !divisionSelect || !coordinacionSelect) {
            console.error('Elementos del formulario no encontrados');
            return;
        }
        
        // Cargar datos iniciales
        loadInitialData(direccionSelect, divisionSelect, coordinacionSelect);
        
        // Configurar eventos
        setupEventListeners(
            direccionSelect, 
            divisionSelect, 
            coordinacionSelect,
            toggleButtons,
            expandAllBtn,
            collapseAllBtn,
            filterForm
        );
        
        // Animar filas de la tabla
        animateTableRows();
    }
    
    function loadInitialData(direccionSelect, divisionSelect, coordinacionSelect) {
        // Obtener datos del contenedor
        const appData = document.getElementById('app-data');
        if (!appData) return;
        
        let direcciones = [];
        let divisiones = [];
        let coordinaciones = [];
        
        try {
            direcciones = JSON.parse(appData.dataset.direcciones || '[]');
            divisiones = JSON.parse(appData.dataset.divisiones || '[]');
            coordinaciones = JSON.parse(appData.dataset.coordinaciones || '[]');
        } catch (error) {
            console.error('Error al parsear datos JSON:', error);
            return;
        }
        
        // Configurar eventos de cambio
        direccionSelect.addEventListener('change', function() {
            cargarDivisiones(this.value, divisiones, divisionSelect);
        });
        
        divisionSelect.addEventListener('change', function() {
            cargarCoordinaciones(this.value, coordinaciones, coordinacionSelect);
        });
        
        // Cargar datos iniciales si hay una dirección seleccionada
        if (direccionSelect.value) {
            cargarDivisiones(direccionSelect.value, divisiones, divisionSelect);
        }
    }
    
    function cargarDivisiones(direccionId, divisiones, divisionSelect) {
        // Limpiar y deshabilitar
        divisionSelect.innerHTML = '<option value="">Todas las divisiones</option>';
        divisionSelect.disabled = true;
        
        // Limpiar coordinaciones también
        const coordinacionSelect = document.getElementById('id_coordinacion');
        if (coordinacionSelect) {
            coordinacionSelect.innerHTML = '<option value="">Todas las coordinaciones</option>';
            coordinacionSelect.disabled = true;
        }
        
        if (!direccionId) return;
        
        // Filtrar divisiones
        const divisionesFiltradas = divisiones.filter(
            division => division.id_direccion == direccionId
        );
        
        // Llenar opciones
        divisionesFiltradas.forEach(division => {
            const option = document.createElement('option');
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
    
    function cargarCoordinaciones(divisionId, coordinaciones, coordinacionSelect) {
        // Limpiar y deshabilitar
        coordinacionSelect.innerHTML = '<option value="">Todas las coordinaciones</option>';
        coordinacionSelect.disabled = true;
        
        if (!divisionId) return;
        
        // Filtrar coordinaciones
        const coordinacionesFiltradas = coordinaciones.filter(
            coordinacion => coordinacion.id_division == divisionId
        );
        
        // Llenar opciones
        coordinacionesFiltradas.forEach(coordinacion => {
            const option = document.createElement('option');
            option.value = coordinacion.id_coordinacion;
            option.textContent = coordinacion.nombre_coordinacion || coordinacion.nombre;
            coordinacionSelect.appendChild(option);
        });
        
        // Habilitar y restaurar valor
        coordinacionSelect.disabled = false;
        const selectedCoordinacion = coordinacionSelect.dataset.selected;
        if (selectedCoordinacion) {
            coordinacionSelect.value = selectedCoordinacion;
        }
    }
    
    function setupEventListeners(
        direccionSelect, 
        divisionSelect, 
        coordinacionSelect,
        toggleButtons,
        expandAllBtn,
        collapseAllBtn,
        filterForm
    ) {
        // Toggle de componentes
        if (toggleButtons && toggleButtons.length > 0) {
            toggleButtons.forEach(button => {
                button.addEventListener('click', toggleComponentDetails);
            });
        }
        
        // Expandir todo
        if (expandAllBtn) {
            expandAllBtn.addEventListener('click', function() {
                toggleAllComponentDetails('expand');
            });
        }
        
        // Colapsar todo
        if (collapseAllBtn) {
            collapseAllBtn.addEventListener('click', function() {
                toggleAllComponentDetails('collapse');
            });
        }
        
        // Formulario de filtros
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                showLoadingState(e.target);
            });
        }
    }
    
    function toggleComponentDetails(event) {
        event.preventDefault();
        const button = event.currentTarget;
        const targetId = button.getAttribute('data-target');
        const targetElement = document.getElementById(targetId);
        
        if (!targetElement) {
            console.error('Elemento objetivo no encontrado:', targetId);
            return;
        }
        
        // Toggle de visibilidad
        if (targetElement.style.display === 'none' || !targetElement.style.display) {
            // Mostrar
            targetElement.style.display = 'block';
            targetElement.style.opacity = '0';
            targetElement.style.transform = 'translateY(-10px)';
            
            // Animación
            requestAnimationFrame(() => {
                targetElement.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                targetElement.style.opacity = '1';
                targetElement.style.transform = 'translateY(0)';
            });
            
            // Cambiar ícono si existe
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-chevron-down');
                icon.classList.add('fa-chevron-up');
            }
            
            button.classList.add('active');
        } else {
            // Ocultar
            targetElement.style.opacity = '0';
            targetElement.style.transform = 'translateY(-10px)';
            
            setTimeout(() => {
                targetElement.style.display = 'none';
            }, 300);
            
            // Cambiar ícono si existe
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.remove('fa-chevron-up');
                icon.classList.add('fa-chevron-down');
            }
            
            button.classList.remove('active');
        }
    }
    
    function toggleAllComponentDetails(action) {
        const toggleButtons = document.querySelectorAll('.toggle-componentes');
        
        toggleButtons.forEach(button => {
            const targetId = button.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            
            if (!targetElement) return;
            
            if (action === 'expand') {
                if (targetElement.style.display === 'none' || !targetElement.style.display) {
                    button.click();
                }
            } else if (action === 'collapse') {
                if (targetElement.style.display === 'block') {
                    button.click();
                }
            }
        });
    }
    
    function showLoadingState(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalHTML = submitButton.innerHTML;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Filtrando...';
            submitButton.disabled = true;
            
            // Restaurar después de 3 segundos máximo
            setTimeout(() => {
                submitButton.innerHTML = originalHTML;
                submitButton.disabled = false;
            }, 3000);
        }
    }
    
    function animateTableRows() {
        const rows = document.querySelectorAll('.equipo-row');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                row.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
});