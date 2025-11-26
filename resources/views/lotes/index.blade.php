<x-app-layout>
    
    <style>
        /* Tabla */
        .table-header {
            background-color: var(--color-primary);
            color: white;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
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

        /* Botones de Acción (Iconos) */
        .btn-icon {
            width: 35px; height: 35px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; border: none; cursor: pointer;
            color: white; font-size: 0.9rem; margin-left: 5px;
        }
        .btn-icon-blue { background-color: #3b82f6; }
        .btn-icon-blue:hover { background-color: #2563eb; }
        
        .btn-icon-yellow { background-color: var(--color-secondary); }
        .btn-icon-yellow:hover { background-color: #9a8235; }

        /* Inputs en el Modal */
        .form-input {
            width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
            padding: 10px 15px; outline: none; transition: all 0.3s;
        }
        .form-input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }

        /* Badges */
        .badge-green { background-color: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
        .badge-gray { background-color: #f3f4f6; color: #374151; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Mis Lotes de Cosecha</h2>
                            <p class="text-gray-500 text-sm mt-1">Gestiona tu inventario de arroz disponible.</p>
                        </div>
                        
                        <a href="{{ route('lotes.create') }}" class="btn-new">
                            <i class="fas fa-plus mr-2"></i> Registrar Nuevo Lote
                        </a>
                    </div>

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Nombre del Lote</th>
                                    <th class="px-6 py-4 text-left font-bold">Sacos (Disp / Total)</th>
                                    <th class="px-6 py-4 text-center font-bold">Calidad (H | Q)</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($lotes as $lote)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        <i class="fas fa-layer-group text-gray-400 mr-2"></i> {{ $lote->nombre_lote }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-bold text-green-700">{{ $lote->cantidad_disponible_sacos }}</span> 
                                        <span class="text-gray-400">/</span> 
                                        <span class="text-gray-600">{{ $lote->cantidad_total_sacos }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-600">
                                        {{ $lote->humedad }}% | {{ $lote->quebrado }}%
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($lote->cantidad_disponible_sacos > 0)
                                            <span class="badge-green">DISPONIBLE</span>
                                        @else
                                            <span class="badge-gray">AGOTADO</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button class="btn-icon btn-icon-blue" onclick="openLoteDetailModal({{ json_encode($lote) }})" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn-icon btn-icon-yellow" onclick="openLoteEditModal({{ json_encode($lote) }})" title="Editar Lote">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-tractor text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-500">No has registrado ningún lote.</p>
                                            <p class="text-sm">Registra tu cosecha para empezar a vender.</p>
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

    <div id="loteEditModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md relative transform transition-all scale-100">
            
            <button onclick="closeLoteEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4 flex items-center">
                <i class="fas fa-pen-to-square mr-2 text-yellow-600"></i> Editar Lote
            </h3>

            <form id="loteEditForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label class="block font-bold text-sm text-gray-700 mb-1">Nombre o Alias del Lote</label>
                        <input id="edit_nombre_lote" type="text" name="nombre_lote" class="form-input" required placeholder="Ej. Cosecha Norte">
                    </div>
                    
                    {{-- Aquí puedes agregar más campos si lo necesitas en el futuro --}}
                </div>

                <div class="flex items-center justify-end mt-6 pt-4 border-t gap-3">
                    <button type="button" onclick="closeLoteEditModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-bold transition">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 font-bold transition">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="loteDetailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 backdrop-blur-sm">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md relative">
            
            <button onclick="closeLoteDetailModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h3 class="text-xl font-bold text-gray-800 border-b pb-3 mb-4 flex items-center" id="modal-lote-title">
                </h3>

            <div class="space-y-3 text-gray-700">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Variedad:</span>
                    <span id="modal-lote-tipo" class="font-bold"></span>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span class="text-gray-500">Total Cosechado:</span>
                    <span class="font-bold"><span id="modal-lote-total"></span> sacos</span>
                </div>
                <div class="flex justify-between bg-green-50 p-2 rounded-lg">
                    <span class="text-green-700 font-bold">Disponible:</span>
                    <span class="font-bold text-green-700"><span id="modal-lote-disponible"></span> sacos</span>
                </div>
                
                <h4 class="font-bold text-sm text-gray-400 uppercase mt-4 mb-2">Calidad</h4>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-2 bg-gray-50 rounded">
                        <div class="text-xs text-gray-500">Humedad</div>
                        <div class="font-bold text-lg"><span id="modal-lote-humedad"></span>%</div>
                    </div>
                    <div class="text-center p-2 bg-gray-50 rounded">
                        <div class="text-xs text-gray-500">Quebrado</div>
                        <div class="font-bold text-lg"><span id="modal-lote-quebrado"></span>%</div>
                    </div>
                </div>
            </div>

            <div class="text-right mt-6">
                <button type="button" onclick="closeLoteDetailModal()" class="px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 font-bold transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>

    <script>
        // --- MODAL DETALLE ---
        function openLoteDetailModal(lote) {
            document.getElementById('modal-lote-title').innerText = lote.nombre_lote;
           document.getElementById("modal-lote-tipo").textContent = lote.tipo_arroz
            ? lote.tipo_arroz.nombre
            : "No especificado"; 
            document.getElementById('modal-lote-total').innerText = lote.cantidad_total_sacos;
            document.getElementById('modal-lote-disponible').innerText = lote.cantidad_disponible_sacos;
            document.getElementById('modal-lote-humedad').innerText = lote.humedad;
            document.getElementById('modal-lote-quebrado').innerText = lote.quebrado;

            // Mostrar
            const modal = document.getElementById('loteDetailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLoteDetailModal() {
            const modal = document.getElementById('loteDetailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // --- MODAL EDITAR ---
        function openLoteEditModal(lote) {
            // Llenar el campo
            document.getElementById('edit_nombre_lote').value = lote.nombre_lote;
            
            // Actualizar la acción del formulario con el ID correcto
            const form = document.getElementById('loteEditForm');
            // Asegúrate de que la ruta base sea correcta. Ejemplo: /lotes/123
            form.action = `/lotes/${lote.id}`;

            // Mostrar
            const modal = document.getElementById('loteEditModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLoteEditModal() {
            const modal = document.getElementById('loteEditModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar al hacer clic fuera del modal
        window.onclick = function(event) {
            const modalEdit = document.getElementById('loteEditModal');
            const modalDetail = document.getElementById('loteDetailModal');
            if (event.target == modalEdit) {
                closeLoteEditModal();
            }
            if (event.target == modalDetail) {
                closeLoteDetailModal();
            }
        }
    </script>

</x-app-layout>