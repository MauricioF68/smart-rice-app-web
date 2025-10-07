<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Campañas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="flex justify-between items-center mb-4">
                        <p class="text-lg">Gestiona tus campañas de compra de arroz.</p>
                        <a href="{{ route('campanas.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Crear Nueva Campaña
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progreso (Sacos)</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($campanas as $campana)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $campana->nombre_campana }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $campana->cantidad_acordada }} / {{ $campana->cantidad_total }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($campana->estado) }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('campanas.aplicaciones', $campana) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            Ver Aplicaciones
                                        </a>

                                        <a href="{{ route('campanas.edit', $campana) }}" class="text-indigo-600 hover:text-indigo-900 ml-4">Editar</a>

                                        <form action="{{ route('campanas.destroy', $campana) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 ml-4">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No has creado ninguna campaña.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="campaignDetailModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-xl p-6 w-full max-w-2xl relative">
            <button onclick="closeCampaignDetailModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl">&times;</button>

            <h3 class="text-xl font-bold border-b dark:border-gray-600 pb-3" id="modal-campaign-title">
                Detalle de la Campaña
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4 mt-4">
                {{-- Columna 1 --}}
                <div class="space-y-3">
                    <p><strong>Total Buscado:</strong> <span id="modal-campaign-total" class="font-semibold"></span> sacos</p>
                    <p><strong>Progreso Actual:</strong> <span id="modal-campaign-progress" class="font-semibold"></span> sacos</p>
                    <p><strong>Precio Base:</strong> <span id="modal-campaign-price" class="font-semibold"></span> S/ por saco</p>
                    <p><strong>Estado:</strong> <span id="modal-campaign-status" class="font-semibold capitalize"></span></p>
                </div>
                {{-- Columna 2 --}}
                <div class="space-y-3">
                    <h4 class="font-bold">Requisitos de Calidad</h4>
                    <p class="text-sm"><strong>Humedad:</strong> <span id="modal-campaign-humidity"></span></p>
                    <p class="text-sm"><strong>Quebrado:</strong> <span id="modal-campaign-breakage"></span></p>
                    <h4 class="font-bold mt-2">Reglas por Agricultor</h4>
                    <p class="text-sm"><strong>Mínimo:</strong> <span id="modal-campaign-min"></span> sacos</p>
                    <p class="text-sm"><strong>Máximo:</strong> <span id="modal-campaign-max"></span> sacos</p>
                </div>
            </div>

            <div class="text-right mt-6">
                <x-secondary-button type="button" onclick="closeCampaignDetailModal()">
                    Cerrar
                </x-secondary-button>
            </div>
        </div>
    </div>
</x-app-layout>