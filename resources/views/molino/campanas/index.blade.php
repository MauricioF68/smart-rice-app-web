<x-app-layout>
    
    <style>
        /* Cabecera */
        .table-header {
            background-color: var(--color-primary); color: white;
            text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;
        }
        .table-row:hover { background-color: #f9f9f9; }

        /* Botón Nuevo */
        .btn-new {
            display: inline-flex; align-items: center;
            background-color: var(--color-primary); color: white;
            padding: 10px 20px; border-radius: 8px; font-weight: 700;
            text-decoration: none; transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-new:hover { background-color: #4b662e; transform: translateY(-2px); }

        /* Barra de Progreso */
        .progress-track { 
            width: 100%; height: 8px; background-color: #e5e7eb; 
            border-radius: 4px; overflow: hidden; margin-top: 6px; 
        }
        .progress-fill { 
            height: 100%; background-color: var(--color-secondary); 
            transition: width 0.5s ease; 
        }

        /* Botones de Acción (Iconos) */
        .btn-icon {
            width: 35px; height: 35px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; border: none; cursor: pointer;
            color: white; font-size: 0.9rem; margin-left: 5px;
        }
        
        .btn-icon-blue { background-color: #3b82f6; } 
        .btn-icon-blue:hover { background-color: #2563eb; transform: translateY(-2px); }

        .btn-icon-gray { background-color: #6b7280; } 
        .btn-icon-gray:hover { background-color: #4b5563; transform: translateY(-2px); }

        .btn-icon-yellow { background-color: var(--color-secondary); } 
        .btn-icon-yellow:hover { background-color: #9a8235; transform: translateY(-2px); }

        .btn-icon-red { background-color: #ef4444; } 
        .btn-icon-red:hover { background-color: #dc2626; transform: translateY(-2px); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-bullhorn mr-3 text-yellow-600"></i> Mis Campañas de Compra
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Gestiona tus requerimientos de materia prima.</p>
                        </div>
                        
                        <a href="{{ route('campanas.create') }}" class="btn-new">
                            <i class="fas fa-plus mr-2"></i> Crear Nueva Campaña
                        </a>
                    </div>

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Campaña</th>
                                    <th class="px-6 py-4 text-left font-bold w-1/3">Progreso (Sacos)</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($campanas as $campana)
                                <tr class="table-row transition-colors">
                                    
                                    {{-- Nombre y Precio --}}
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $campana->nombre_campana }}
                                        <div class="text-xs text-gray-500 mt-1 flex items-center">
                                            <i class="fas fa-tag mr-1 text-green-600"></i> 
                                            Base: S/ {{ number_format($campana->precio_base, 2) }}
                                        </div>
                                    </td>
                                    
                                    {{-- Barra de Progreso --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex justify-between text-xs font-bold text-gray-600 mb-1">
                                            <span class="text-green-700">{{ $campana->cantidad_acordada }} comprados</span>
                                            <span>Meta: {{ $campana->cantidad_total }}</span>
                                        </div>
                                        <div class="progress-track">
                                            @php
                                                $percent = $campana->cantidad_total > 0 ? ($campana->cantidad_acordada / $campana->cantidad_total) * 100 : 0;
                                            @endphp
                                            <div class="progress-fill" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-3 py-1 text-xs font-bold rounded-full border 
                                            {{ $campana->estado === 'activa' ? 'bg-green-50 text-green-700 border-green-200' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                            {{ ucfirst($campana->estado) }}
                                        </span>
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center">
                                            
                                            <a href="{{ route('campanas.aplicaciones', $campana) }}" class="btn-icon btn-icon-blue" title="Gestionar Postulantes">
                                                <i class="fas fa-users"></i>
                                            </a>

                                            <button class="btn-icon btn-icon-gray" 
                                                data-json="{!! htmlspecialchars(json_encode($campana), ENT_QUOTES, 'UTF-8') !!}"
                                                onclick="openCampaignDetailModal(this)" 
                                                title="Ver Detalles">
                                                <i class="fas fa-info-circle"></i>
                                            </button>

                                            <a href="{{ route('campanas.edit', $campana) }}" class="btn-icon btn-icon-yellow" title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <form action="{{ route('campanas.destroy', $campana) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de eliminar esta campaña?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-red" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-box-open text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-500">No has creado ninguna campaña.</p>
                                            <p class="text-sm mb-4">Publica tu primera necesidad de compra.</p>
                                            <a href="{{ route('campanas.create') }}" class="btn-new bg-gray-500 hover:bg-gray-600 text-sm py-2">
                                                Crear Campaña
                                            </a>
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

    <div id="campaignDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg relative transform scale-100">
            
            <button onclick="closeCampaignDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl transition">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4 flex items-center">
                <i class="fas fa-file-alt mr-2 text-gray-500"></i> Detalle de Campaña
            </h3>

            <h4 class="text-lg font-bold text-green-700 mb-4" id="modal-campaign-title"></h4>

            <div class="grid grid-cols-2 gap-6 text-gray-700 text-sm">
                <div>
                    <p class="text-gray-500 font-bold uppercase text-xs">Meta Total</p>
                    <p class="text-lg font-bold"><span id="modal-campaign-total"></span> sacos</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold uppercase text-xs">Progreso</p>
                    <p class="text-lg font-bold text-green-600"><span id="modal-campaign-progress"></span> sacos</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold uppercase text-xs">Precio Base</p>
                    <p class="text-lg font-bold">S/ <span id="modal-campaign-price"></span></p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold uppercase text-xs">Estado</p>
                    <span id="modal-campaign-status" class="font-bold uppercase bg-gray-100 px-2 py-1 rounded text-xs"></span>
                </div>
            </div>

            <div class="mt-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h5 class="font-bold text-gray-700 mb-2 border-b border-gray-200 pb-1 text-xs uppercase">Requisitos de Calidad</h5>
                <div class="flex justify-between text-sm">
                    <p>Humedad Máx: <strong><span id="modal-campaign-humidity"></span>%</strong></p>
                    <p>Quebrado Máx: <strong><span id="modal-campaign-breakage"></span>%</strong></p>
                </div>
            </div>

            <div class="mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h5 class="font-bold text-gray-700 mb-2 border-b border-gray-200 pb-1 text-xs uppercase">Límites por Agricultor</h5>
                <div class="flex justify-between text-sm">
                    <p>Mínimo: <strong><span id="modal-campaign-min"></span></strong> sacos</p>
                    <p>Máximo: <strong><span id="modal-campaign-max"></span></strong> sacos</p>
                </div>
            </div>

            <div class="text-right mt-6">
                <button type="button" onclick="closeCampaignDetailModal()" class="px-6 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-bold transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        function openCampaignDetailModal(button) {
            try {
                // Leer datos de forma segura
                const jsonString = button.getAttribute('data-json');
                const campana = JSON.parse(jsonString);

                document.getElementById('modal-campaign-title').innerText = campana.nombre_campana;
                document.getElementById('modal-campaign-total').innerText = campana.cantidad_total;
                document.getElementById('modal-campaign-progress').innerText = campana.cantidad_acordada;
                document.getElementById('modal-campaign-price').innerText = parseFloat(campana.precio_base).toFixed(2);
                document.getElementById('modal-campaign-status').innerText = campana.estado;
                
                document.getElementById("modal-campaign-humidity").textContent = `${campana.humedad_min || "N/A"}% - ${campana.humedad_max || "N/A"}%`;
                document.getElementById("modal-campaign-breakage").textContent = `${campana.quebrado_min || "N/A"}% - ${campana.quebrado_max || "N/A"}%`;
                
                document.getElementById("modal-campaign-min").textContent = campana.min_sacos_por_agricultor || "N/A";
                document.getElementById("modal-campaign-max").textContent = campana.max_sacos_por_agricultor || "N/A";

                const modal = document.getElementById('campaignDetailModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } catch (e) {
                console.error('Error al abrir modal:', e);
            }
        }

        function closeCampaignDetailModal() {
            const modal = document.getElementById('campaignDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar al hacer click fuera
        window.onclick = function(event) {
            const modal = document.getElementById('campaignDetailModal');
            if (event.target == modal) closeCampaignDetailModal();
        }
    </script>

</x-app-layout>