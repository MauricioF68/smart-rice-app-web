<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mercado de Preventas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p>Explora las ofertas de los agricultores y haz tu propuesta de compra.</p>
                    
                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Agricultor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sacos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Sugerido</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($preventas as $preventa)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">PV-{{ $preventa->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $preventa->user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $preventa->cantidad_sacos }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">S/ {{ number_format($preventa->precio_por_saco, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            {{-- Este botón ahora pasará el objeto completo de la preventa al JavaScript --}}
                                            <button class="text-indigo-600 hover:text-indigo-900 font-bold" onclick="openProposalModal('{{ json_encode($preventa) }}')">
                                                Ver y Ofertar
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No hay preventas activas en el mercado en este momento.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="proposalModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-xl p-6 w-full max-w-2xl relative">
            <button id="closeModalBtn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl">&times;</button>
            
            {{-- Título dinámico --}}
            <h3 class="text-xl font-bold border-b dark:border-gray-600 pb-3">Detalle de la Preventa <span id="modalPreventaId"></span></h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                {{-- Columna de Detalles --}}
                <div class="space-y-3">
                    
                    <p><strong>Cantidad:</strong> <span id="modal-cantidad" class="font-semibold"></span> sacos</p>
                    <p><strong>Precio Sugerido:</strong> <span id="modal-precio" class="font-semibold"></span> S/ por saco</p>
                    <p><strong>Humedad:</strong> <span id="modal-humedad" class="font-semibold"></span>%</p>
                    <p><strong>Quebrado:</strong> <span id="modal-quebrado" class="font-semibold"></span>%</p>
                </div>
                {{-- Columna de Notas y Acciones --}}
                <div class="space-y-4">
                    <div>
                        <strong>Notas Adicionales:</strong>
                        <p id="modal-notas" class="text-sm text-gray-600 dark:text-gray-400 mt-1 italic">
                            Cargando...
                        </p>
                    </div>

                    {{-- ACCIONES --}}
                    <div class="border-t dark:border-gray-600 pt-4 space-x-2 text-right">
                        {{-- Formulario para Aceptar Oferta --}}
                        <form id="acceptOfferForm" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas aceptar esta oferta con sus términos actuales?');">
                            @csrf
                            <x-primary-button type="submit">Aceptar Oferta</x-primary-button>
                        </form>

                        {{-- Botón para abrir el formulario de contrapropuesta --}}
                        <x-secondary-button type="button" id="showCounterProposalBtn">
                            Hacer Contrapropuesta
                        </x-secondary-button>
                    </div>
                </div>
            </div>

            {{-- Formulario de Contrapropuesta (Oculto por defecto) --}}
            <div id="counterProposalFormContainer" class="hidden mt-4 pt-4 border-t dark:border-gray-600">
                 <form method="POST" action="{{ route('propuestas.store') }}">
                    @csrf
                    <input type="hidden" id="preventa_id_input" name="preventa_id">
                    <p class="font-semibold mb-2">Tu Contrapropuesta:</p>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="cantidad_sacos_propuesta" :value="__('Nueva Cantidad de Sacos')" />
                            <x-text-input id="cantidad_sacos_propuesta" class="block mt-1 w-full" type="number" name="cantidad_sacos_propuesta" required />
                        </div>
                        <div>
                            <x-input-label for="precio_por_saco_propuesta" :value="__('Nuevo Precio por Saco (S/)')" />
                            <x-text-input id="precio_por_saco_propuesta" class="block mt-1 w-full" type="number" name="precio_por_saco_propuesta" step="0.01" required />
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>Enviar Contrapropuesta</x-primary-button>
                    </div>
                </form>
            </div>

        </div>
    </div>
     </x-app-layout>