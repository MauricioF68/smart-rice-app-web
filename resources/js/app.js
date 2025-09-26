import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// =======================================================
// ===== LÓGICA PARA EL MODAL DE DETALLES Y PROPUESTAS =====
// =======================================================

// Espera a que el documento HTML esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
    
    // Elementos del Modal
    const modal = document.getElementById('proposalModal');
    if (!modal) return; // Si no existe el modal en la página actual, no hacer nada

    const closeModalBtn = document.getElementById('closeModalBtn');
    const showCounterProposalBtn = document.getElementById('showCounterProposalBtn');
    const counterProposalForm = document.getElementById('counterProposalFormContainer');

    // Elementos para rellenar con datos
    const modalPreventaId = document.getElementById('modalPreventaId');
    const modalAgricultor = document.getElementById('modal-agricultor');
    const modalCantidad = document.getElementById('modal-cantidad');
    const modalPrecio = document.getElementById('modal-precio');
    const modalHumedad = document.getElementById('modal-humedad');
    const modalQuebrado = document.getElementById('modal-quebrado');
    const modalNotas = document.getElementById('modal-notas');
    const preventaIdInput = document.getElementById('preventa_id_input');
    const acceptOfferForm = document.getElementById('acceptOfferForm');
    
    // --- FUNCIÓN PRINCIPAL PARA ABRIR EL MODAL ---
    window.openProposalModal = function(preventaString) {
        const preventa = JSON.parse(preventaString);
        // 1. Rellenar los campos del modal con los datos de la preventa
        modalPreventaId.textContent = `PV-${preventa.id}`;
        //modalAgricultor.textContent = preventa.user.name;
        modalCantidad.textContent = preventa.cantidad_sacos;
        modalPrecio.textContent = parseFloat(preventa.precio_por_saco).toFixed(2);
        modalHumedad.textContent = parseFloat(preventa.humedad).toFixed(2);
        modalQuebrado.textContent = parseFloat(preventa.quebrado).toFixed(2);
        modalNotas.textContent = preventa.notas || 'Sin notas adicionales.';
        console.log(preventa);
        console.log("cantidad de sacos = " + preventa.cantidad_sacos);

        // 2. Configurar los formularios con los IDs y rutas correctas
        preventaIdInput.value = preventa.id; // Para el form de contrapropuesta
        
        // Construimos la URL para el formulario de Aceptar Oferta dinámicamente
        const acceptUrl = `/preventas/${preventa.id}/aceptar`;
        acceptOfferForm.action = acceptUrl;

        // 3. Reiniciar y mostrar el modal
        counterProposalForm.classList.add('hidden'); // Ocultar siempre el form de contrapropuesta al abrir
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // --- FUNCIÓN PARA CERRAR EL MODAL ---
    window.closeProposalModal = function() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // --- EVENT LISTENERS (ESCUCHADORES DE EVENTOS) ---
    // Para el botón de cerrar (X)
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeProposalModal);
    }

    // Para mostrar el formulario de contrapropuesta
    if (showCounterProposalBtn) {
        showCounterProposalBtn.addEventListener('click', () => {
            counterProposalForm.classList.toggle('hidden');
        });
    }

    // Para cerrar haciendo clic en el fondo oscuro
    modal.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeProposalModal();
        }
    });
});