<x-app-layout>
    
    <style>
        /* Cabecera de Tabla Verde */
        .table-header {
            background-color: var(--color-primary);
            color: white;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table-row:hover { background-color: #f9f9f9; }

        /* Botón Participar */
        .btn-participate {
            background-color: var(--color-primary); color: white;
            padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem;
            border: none; cursor: pointer; transition: all 0.2s;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-participate:hover { background-color: #4b662e; transform: translateY(-1px); }

        /* Barra de Progreso */
        .progress-container {
            width: 100%; background-color: #e5e7eb; border-radius: 10px; height: 8px; overflow: hidden;
            margin-top: 5px;
        }
        .progress-bar {
            height: 100%; background-color: var(--color-secondary); /* Amarillo para progreso */
            transition: width 0.5s ease;
        }

        /* Inputs del Modal */
        .form-input, .form-select {
            width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
            padding: 10px 15px; outline: none; transition: all 0.3s;
        }
        .form-input:focus, .form-select:focus { 
            border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); 
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">
                    
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-store text-yellow-600 mr-3"></i> Mercado de Campañas
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Explora las campañas de compra activas y vende tu arroz directamente a los molinos.</p>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Molino</th>
                                    <th class="px-6 py-4 text-left font-bold">Campaña</th>
                                    <th class="px-6 py-4 text-left font-bold w-1/4">Progreso (Sacos)</th>
                                    <th class="px-6 py-4 text-left font-bold">Precio Base</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($campanas as $campana)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold mr-3">
                                                {{ substr($campana->user->name, 0, 1) }}
                                            </div>
                                            {{ $campana->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-semibold">
                                        {{ $campana->nombre_campana }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-between text-xs text-gray-500 mb-1">
                                            <span>{{ $campana->cantidad_acordada }}</span>
                                            <span>{{ $campana->cantidad_total }}</span>
                                        </div>
                                        <div class="progress-container">
                                            @php
                                                $porcentaje = $campana->cantidad_total > 0 ? ($campana->cantidad_acordada / $campana->cantidad_total) * 100 : 0;
                                            @endphp
                                            <div class="progress-bar" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700">
                                        S/ {{ number_format($campana->precio_base, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($campana->lotes_compatibles->isNotEmpty())
                                            
                                            <button class="btn-participate"
                                                data-campana="{!! htmlspecialchars(json_encode($campana), ENT_QUOTES, 'UTF-8') !!}"
                                                data-lotes="{!! htmlspecialchars(json_encode($campana->lotes_compatibles), ENT_QUOTES, 'UTF-8') !!}"
                                                onclick="openApplyModal(this)">
                                                Participar <i class="fas fa-arrow-right ml-1"></i>
                                            </button>

                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded-full text-xs font-bold border border-gray-200 cursor-not-allowed" title="No tienes lotes que cumplan los requisitos de esta campaña">
                                                No compatible
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-store-slash text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-500">No hay campañas activas.</p>
                                            <p class="text-sm">Los molinos publicarán sus requerimientos aquí pronto.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div id="applyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md relative">
            
            <button onclick="closeApplyModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4 flex items-center">
                <i class="fas fa-handshake mr-2 text-green-600"></i> Aplicar a Campaña
            </h3>

            <form id="applyForm" method="POST" action="">
                @csrf
                
                <div class="space-y-4">
                    
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Selecciona tu Lote Compatible</label>
                        <select id="lote_id" name="lote_id" class="form-select" required>
                            </select>
                    </div>

                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Cantidad de Sacos a Comprometer</label>
                        <input id="cantidad_sacos" type="number" name="cantidad_sacos" class="form-input" required placeholder="Ej. 500" />
                        <p class="text-xs text-gray-500 mt-1" id="max-sacos-info"></p>
                    </div>

                    <div class="bg-yellow-50 p-3 rounded-lg text-xs text-yellow-800 border border-yellow-100">
                        <i class="fas fa-info-circle mr-1"></i>
                        <strong>Términos:</strong> Al aplicar, aceptas los requisitos de calidad y el precio base de la campaña. El acuerdo final está sujeto a la aprobación del molino.
                    </div>

                </div>

                <div class="flex items-center justify-end mt-6 pt-4 border-t gap-3">
                    <button type="button" onclick="closeApplyModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-bold transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold transition shadow-md">
                        Enviar Aplicación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function getJsonData(button, attr) {
            try {
                return JSON.parse(button.getAttribute(attr));
            } catch (e) {
                console.error("Error parseando JSON:", e);
                return null;
            }
        }

        function openApplyModal(button) {
            const campana = getJsonData(button, 'data-campana');
            const lotes = getJsonData(button, 'data-lotes');

            if (!campana || !lotes) return;

            // 1. Actualizar la ruta del formulario
            // Asegúrate de tener esta ruta definida: Route::post('/campanas/{campana}/aplicar', ...)
            const form = document.getElementById('applyForm');
            form.action = `/campanas/${campana.id}/aplicar`;

            // 2. Llenar el select de lotes
            const selectLote = document.getElementById('lote_id');
            selectLote.innerHTML = '<option value="">-- Elige un lote --</option>';
            
            lotes.forEach(lote => {
                const option = document.createElement('option');
                option.value = lote.id;
                option.textContent = `${lote.nombre_lote} (${lote.cantidad_disponible_sacos} disp.)`;
                // Guardamos la cantidad máxima en un atributo del option para usarlo luego si queremos validar
                option.setAttribute('data-max', lote.cantidad_disponible_sacos);
                selectLote.appendChild(option);
            });

            // 3. Mostrar el modal
            const modal = document.getElementById('applyModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeApplyModal() {
            const modal = document.getElementById('applyModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('applyModal');
            if (event.target == modal) closeApplyModal();
        }
    </script>

</x-app-layout>