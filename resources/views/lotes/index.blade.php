<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Lotes de Cosecha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center mb-4">
                        <p class="text-lg">Este es tu inventario de arroz disponible.</p>
                        <a href="{{ route('lotes.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Registrar Nuevo Lote
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre del Lote</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Sacos (Disp / Total)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($lotes as $lote)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $lote->nombre_lote }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $lote->cantidad_disponible_sacos }} / {{ $lote->cantidad_total_sacos }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($lote->estado) }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-indigo-600 hover:text-indigo-900" onclick="openLoteDetailModal('{{ json_encode($lote) }}')">
                                            Ver
                                        </button>
                                        <button class="text-yellow-600 hover:text-yellow-900 ml-4" onclick="openLoteEditModal('{{ json_encode($lote) }}')">
                                            Editar
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No has registrado ningún lote.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loteEditModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-xl p-6 w-full max-w-lg relative">
            <button onclick="closeLoteEditModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl">&times;</button>

            <h3 class="text-xl font-bold border-b dark:border-gray-600 pb-3">
                Editar Lote
            </h3>

            <form id="loteEditForm" method="POST" action="">
                @csrf
                @method('PUT')

                <div class="mt-6 space-y-4">
                    <div>
                        <x-input-label for="edit_nombre_lote" value="Nombre o Alias del Lote" />
                        <x-text-input id="edit_nombre_lote" class="block mt-1 w-full" type="text" name="nombre_lote" required />
                    </div>

                    {{-- Aquí irían los demás campos editables si los necesitáramos --}}

                    <div class="flex items-center justify-end mt-6">
                        <x-secondary-button type="button" onclick="closeLoteEditModal()">
                            Cancelar
                        </x-secondary-button>
                        <x-primary-button class="ms-4">
                            Actualizar Lote
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="loteDetailModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-xl p-6 w-full max-w-lg relative">
            <button onclick="closeLoteDetailModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl">&times;</button>

            <h3 class="text-xl font-bold border-b dark:border-gray-600 pb-3" id="modal-lote-title">
                Detalle del Lote
            </h3>

            <div class="mt-4 space-y-3">
                <p><strong>Variedad de Arroz:</strong> <span id="modal-lote-tipo" class="font-semibold"></span></p>
                <p><strong>Cantidad Total:</strong> <span id="modal-lote-total" class="font-semibold"></span> sacos</p>
                <p><strong>Cantidad Disponible:</strong> <span id="modal-lote-disponible" class="font-semibold text-green-500"></span> sacos</p>
                <h4 class="font-bold pt-2">Calidad Estimada</h4>
                <p class="text-sm"><strong>Humedad:</strong> <span id="modal-lote-humedad"></span>%</p>
                <p class="text-sm"><strong>Quebrado:</strong> <span id="modal-lote-quebrado"></span>%</p>
                <p class="text-sm"><strong>Estado:</strong> <span id="modal-lote-estado" class="font-semibold capitalize"></span></p>
            </div>

            <div class="text-right mt-6">
                <x-secondary-button type="button" onclick="closeLoteDetailModal()">
                    Cerrar
                </x-secondary-button>
            </div>
        </div>
    </div>
</x-app-layout>