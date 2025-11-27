<x-app-layout>
    
    <style>
        .table-header { background-color: var(--color-primary); color: white; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .table-row:hover { background-color: #f9f9f9; }
        .btn-offer { background-color: var(--color-primary); color: white; padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; }
        .btn-offer:hover { background-color: #4b662e; transform: translateY(-1px); }
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 15px; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">¡Atención!</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-search-dollar mr-3 text-green-600"></i> Mercado de Agricultores
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Explora las ofertas de los agricultores y haz tu propuesta.</p>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">ID</th>
                                    <th class="px-6 py-4 text-left font-bold">Agricultor</th>
                                    <th class="px-6 py-4 text-left font-bold">Sacos</th>
                                    <th class="px-6 py-4 text-left font-bold">Precio Sugerido</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($preventas as $preventa)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono">#PV-{{ $preventa->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold mr-3">
                                                {{ substr($preventa->user->name, 0, 1) }}
                                            </div>
                                            {{ $preventa->user->name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-bold">{{ $preventa->cantidad_sacos }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700">S/ {{ number_format($preventa->precio_por_saco, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button type="button" class="btn-offer" 
                                            data-preventa="{!! htmlspecialchars(json_encode($preventa), ENT_QUOTES, 'UTF-8') !!}"
                                            onclick="openProposalModal(this)">
                                            Ver y Ofertar <i class="fas fa-hand-holding-usd"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">No hay preventas activas.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="proposalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm" style="display: none;">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-2xl relative max-h-[90vh] overflow-y-auto">
            
            <button type="button" onclick="closeProposalModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 text-xl">
                <i class="fas fa-times"></i>
            </button>
            
            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4 flex items-center">
                <i class="fas fa-tag mr-2 text-green-600"></i> Detalle de Preventa <span id="modalPreventaId" class="ml-2 text-gray-500 text-base font-normal"></span>
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <h4 class="font-bold text-gray-700 mb-3 uppercase text-xs">Datos de la Oferta</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Cantidad:</span> <span class="font-bold text-gray-800"><span id="modal-cantidad"></span> sacos</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Precio Sugerido:</span> <span class="font-bold text-green-700">S/ <span id="modal-precio"></span></span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Humedad:</span> <span class="font-bold text-gray-800"><span id="modal-humedad"></span>%</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Quebrado:</span> <span class="font-bold text-gray-800"><span id="modal-quebrado"></span>%</span></div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-gray-700 mb-2 uppercase text-xs">Notas del Agricultor</h4>
                    <p id="modal-notas" class="text-sm text-gray-600 italic bg-yellow-50 p-3 rounded border border-yellow-100 min-h-[80px]">Sin notas.</p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end items-center gap-4">
                
                <button type="button" onclick="toggleCounterProposal()" class="px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 font-bold shadow-sm transition">
                    Hacer Contrapropuesta
                </button>

                <form id="acceptForm" method="POST" action="{{ route('propuestas.store') }}" onsubmit="return confirm('¿Confirmar compra con los términos actuales?');">
                    @csrf
                    <input type="hidden" name="preventa_id" id="accept_preventa_id">
                    <input type="hidden" name="cantidad_sacos_propuesta" id="accept_cantidad">
                    <input type="hidden" name="precio_por_saco_propuesta" id="accept_precio">
                    
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-bold shadow-md transition flex items-center">
                        <i class="fas fa-check mr-2"></i> Aceptar Oferta
                    </button>
                </form>
            </div>

            <div id="counterProposalContainer" class="hidden mt-4 bg-yellow-50 p-5 rounded-xl border border-yellow-200 animation-fade-in">
                <h4 class="font-bold text-yellow-800 mb-3 border-b border-yellow-200 pb-2">Tu Contrapropuesta:</h4>
                
                <form method="POST" action="{{ route('propuestas.store') }}">
                    @csrf
                    <input type="hidden" id="counter_preventa_id" name="preventa_id">
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-bold text-xs text-gray-600 mb-1">Cantidad (Sacos)</label>
                            <input id="counter_cantidad" type="number" name="cantidad_sacos_propuesta" class="form-input" required />
                        </div>
                        <div>
                            <label class="block font-bold text-xs text-gray-600 mb-1">Precio por Saco (S/)</label>
                            <input id="counter_precio" type="number" step="0.01" name="precio_por_saco_propuesta" class="form-input" required />
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-bold shadow">
                            Enviar Contrapropuesta <i class="fas fa-paper-plane ml-1"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        // Función Global
        window.openProposalModal = function(button) {
            try {
                // 1. Obtener datos
                const jsonString = button.getAttribute('data-preventa');
                const preventa = JSON.parse(jsonString);
                console.log("Datos de preventa:", preventa);

                // 2. Llenar textos visuales
                document.getElementById('modalPreventaId').innerText = '#' + preventa.id;
                document.getElementById('modal-cantidad').innerText = parseFloat(preventa.cantidad_sacos).toFixed(1);
                document.getElementById('modal-precio').innerText = parseFloat(preventa.precio_por_saco).toFixed(2);
                document.getElementById('modal-humedad').innerText = preventa.humedad;
                document.getElementById('modal-quebrado').innerText = preventa.quebrado;
                document.getElementById('modal-notas').innerText = preventa.notas || 'Sin notas.';

                console.log(preventa.cantidad_sacos);

                // 3. LLENAR FORMULARIO ACEPTAR (IMPORTANTE)
                document.getElementById('accept_preventa_id').value = preventa.id;
                document.getElementById('accept_cantidad').value = preventa.cantidad_sacos;
                document.getElementById('accept_precio').value = preventa.precio_por_saco;

                // 4. LLENAR FORMULARIO CONTRAPROPUESTA
                document.getElementById('counter_preventa_id').value = preventa.id;
                document.getElementById('counter_cantidad').value = preventa.cantidad_sacos;
                document.getElementById('counter_precio').value = preventa.precio_por_saco;

                // 5. Mostrar
                const modal = document.getElementById('proposalModal');
                modal.style.display = 'flex';
                modal.classList.remove('hidden');
                
                // Ocultar contrapropuesta al abrir
                document.getElementById('counterProposalContainer').classList.add('hidden');

            } catch (e) {
                console.error("Error JS:", e);
                alert("Error al cargar datos. Revisa la consola.");
            }
        }

        window.closeProposalModal = function() {
            const modal = document.getElementById('proposalModal');
            modal.style.display = 'none';
            modal.classList.add('hidden');
        }

        window.toggleCounterProposal = function() {
            const container = document.getElementById('counterProposalContainer');
            if (container.classList.contains('hidden')) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        window.onclick = function(event) {
            const modal = document.getElementById('proposalModal');
            if (event.target == modal) closeProposalModal();
        }
    </script>
</x-app-layout>