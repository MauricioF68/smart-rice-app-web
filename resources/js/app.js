import "./bootstrap";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();

// =======================================================
// ===== LÓGICA PARA EL MODAL DE DETALLES Y PROPUESTAS =====
// =======================================================
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("proposalModal");
    if (!modal) return;

    const closeModalBtn = document.getElementById("closeModalBtn");
    const showCounterProposalBtn = document.getElementById(
        "showCounterProposalBtn"
    );
    const counterProposalForm = document.getElementById(
        "counterProposalFormContainer"
    );
    const modalPreventaId = document.getElementById("modalPreventaId");
    const modalAgricultor = document.getElementById("modal-agricultor"); // Asegúrate que este ID exista en tu HTML
    const modalCantidad = document.getElementById("modal-cantidad");
    const modalPrecio = document.getElementById("modal-precio");
    const modalHumedad = document.getElementById("modal-humedad");
    const modalQuebrado = document.getElementById("modal-quebrado");
    const modalNotas = document.getElementById("modal-notas");
    const preventaIdInput = document.getElementById("preventa_id_input");
    const acceptOfferForm = document.getElementById("acceptOfferForm");

    // --- FUNCIÓN PARA ABRIR EL MODAL (CORREGIDA) ---
    window.openProposalModal = function (preventaString) {
        const preventa = JSON.parse(preventaString);

        modalPreventaId.textContent = `PV-${preventa.id}`;
        if (modalAgricultor) modalAgricultor.textContent = preventa.user.name; // Agregado para el nuevo modal
        modalCantidad.textContent = preventa.cantidad_sacos;
        modalPrecio.textContent = parseFloat(preventa.precio_por_saco).toFixed(
            2
        );
        modalHumedad.textContent = parseFloat(preventa.humedad).toFixed(2);
        modalQuebrado.textContent = parseFloat(preventa.quebrado).toFixed(2);
        modalNotas.textContent = preventa.notas || "Sin notas adicionales.";

        preventaIdInput.value = preventa.id;
        acceptOfferForm.action = `/preventas/${preventa.id}/aceptar`;

        counterProposalForm.classList.add("hidden");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    // --- FUNCIÓN PARA CERRAR EL MODAL ---
    window.closeProposalModal = function () {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    };

    // --- EVENT LISTENERS ---
    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", closeProposalModal);
    }

    if (showCounterProposalBtn) {
        showCounterProposalBtn.addEventListener("click", () => {
            counterProposalForm.classList.toggle("hidden");
        });
    }

    modal.addEventListener("click", function (event) {
        if (event.target === modal) {
            closeProposalModal();
        }
    });
});

// =======================================================
// ===== LÓGICA PARA EL MODAL DE DETALLE DE CAMPAÑA =====
// =======================================================
document.addEventListener("DOMContentLoaded", function () {
    const campaignModal = document.getElementById("campaignDetailModal");
    if (!campaignModal) return;

    // --- FUNCIÓN PARA ABRIR EL MODAL DE DETALLE DE CAMPAÑA ---
    window.openCampaignDetailModal = function (campanaString) {
        const campana = JSON.parse(campanaString);

        // Rellenar los campos del modal
        document.getElementById(
            "modal-campaign-title"
        ).textContent = `Detalle: ${campana.nombre_campana}`;
        document.getElementById("modal-campaign-total").textContent =
            campana.cantidad_total;
        document.getElementById("modal-campaign-progress").textContent =
            campana.cantidad_acordada;
        document.getElementById(
            "modal-campaign-price"
        ).textContent = `S/ ${parseFloat(campana.precio_base).toFixed(2)}`;
        document.getElementById("modal-campaign-status").textContent =
            campana.estado;
        document.getElementById("modal-campaign-humidity").textContent = `${
            campana.humedad_min || "N/A"
        }% - ${campana.humedad_max || "N/A"}%`;
        document.getElementById("modal-campaign-breakage").textContent = `${
            campana.quebrado_min || "N/A"
        }% - ${campana.quebrado_max || "N/A"}%`;
        document.getElementById("modal-campaign-min").textContent =
            campana.min_sacos_por_agricultor || "N/A";
        document.getElementById("modal-campaign-max").textContent =
            campana.max_sacos_por_agricultor || "N/A";

        campaignModal.classList.remove("hidden");
        campaignModal.classList.add("flex");
    };

    // --- FUNCIÓN PARA CERRAR EL MODAL ---
    window.closeCampaignDetailModal = function () {
        campaignModal.classList.add("hidden");
        campaignModal.classList.remove("flex");
    };
});

/* =======================================================
// ===== LÓGICA PARA EL MODAL DE EDICIÓN DE LOTE =====
// =======================================================
document.addEventListener("DOMContentLoaded", function () {
    const loteEditModal = document.getElementById("loteEditModal");
    if (!loteEditModal) return;

    const loteEditForm = document.getElementById("loteEditForm");
    const editNombreLoteInput = document.getElementById("edit_nombre_lote");

    // --- FUNCIÓN PARA ABRIR EL MODAL DE EDICIÓN ---
    window.openLoteEditModal = function (loteString) {
        const lote = JSON.parse(loteString);

        // Rellenar el formulario del modal
        editNombreLoteInput.value = lote.nombre_lote;

        // Construir la URL para la acción del formulario
        const updateUrl = `/lotes/${lote.id}`;
        loteEditForm.action = updateUrl;

        loteEditModal.classList.remove("hidden");
        loteEditModal.classList.add("flex");
    };

    // --- FUNCIÓN PARA CERRAR EL MODAL ---
    window.closeLoteEditModal = function () {
        loteEditModal.classList.add("hidden");
        loteEditModal.classList.remove("flex");
    };
});*/

/* =======================================================
// ===== LÓGICA PARA EL MODAL DE DETALLE DE LOTE =====
// =======================================================
document.addEventListener("DOMContentLoaded", function () {
    const loteDetailModal = document.getElementById("loteDetailModal");
    if (!loteDetailModal) return;

    window.openLoteDetailModal = function (loteString) {
        const lote = JSON.parse(loteString);

        // Rellenar los campos del modal
        document.getElementById(
            "modal-lote-title"
        ).textContent = `Detalle: ${lote.nombre_lote}`;
        document.getElementById("modal-lote-tipo").textContent = lote.tipo_arroz
            ? lote.tipo_arroz.nombre
            : "No especificado";
        document.getElementById("modal-lote-total").textContent =
            lote.cantidad_total_sacos;
        document.getElementById("modal-lote-disponible").textContent =
            lote.cantidad_disponible_sacos;
        document.getElementById("modal-lote-humedad").textContent = parseFloat(
            lote.humedad
        ).toFixed(2);
        document.getElementById("modal-lote-quebrado").textContent = parseFloat(
            lote.quebrado
        ).toFixed(2);
        document.getElementById("modal-lote-estado").textContent = lote.estado;

        loteDetailModal.classList.remove("hidden");
        loteDetailModal.classList.add("flex");
    };

    window.closeLoteDetailModal = function () {
        loteDetailModal.classList.add("hidden");
        loteDetailModal.classList.remove("flex");
    };
});*/

/*/ =======================================================
// ===== LÓGICA PARA EL MODAL DE APLICAR A CAMPAÑA =====
// =======================================================

// --- FUNCIÓN GLOBAL PARA ABRIR EL MODAL ---
window.openApplyModal = function (campanaString, lotesCompatiblesString) {
    const campana = JSON.parse(campanaString);
    const lotesCompatibles = JSON.parse(lotesCompatiblesString);
    const modal = document.getElementById("applyModal");
    if (!modal) return;

    const loteSelect = document.getElementById("lote_id");
    document.getElementById("cantidad_sacos").value =
        campana.min_sacos_por_agricultor || 1;

    // Limpiar opciones anteriores del select
    loteSelect.innerHTML = '<option value="">-- Selecciona tu lote --</option>';

    // Llenar el select con los lotes compatibles
    lotesCompatibles.forEach((lote) => {
        const option = document.createElement("option");
        option.value = lote.id;
        option.textContent = `${lote.nombre_lote} (${lote.cantidad_disponible_sacos} sacos disponibles)`;
        loteSelect.appendChild(option);
    });

    // ... dentro de la función openApplyModal ...

    const cantidadInput = document.getElementById("cantidad_sacos");

    // 1. Establecer el valor inicial con el mínimo requerido
    cantidadInput.value = campana.min_sacos_por_agricultor || 1;

    // 2. Establecer el atributo 'min' para la validación del navegador
    cantidadInput.min = campana.min_sacos_por_agricultor || 1;

    // 3. Establecer el atributo 'max' con el máximo definido en la campaña
    if (campana.max_sacos_por_agricultor) {
        cantidadInput.max = campana.max_sacos_por_agricultor;
    } else {
        // Si no hay máximo, no establecemos uno
        cantidadInput.removeAttribute("max");
    }

    // Configurar el formulario
    document.getElementById(
        "applyForm"
    ).action = `/campanas/${campana.id}/aplicar`;

    // Mostrar el modal
    modal.classList.remove("hidden");
    modal.classList.add("flex");
};

// --- FUNCIÓN GLOBAL PARA CERRAR EL MODAL ---
window.closeApplyModal = function () {
    const modal = document.getElementById("applyModal");
    if (modal) {
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
};*/
