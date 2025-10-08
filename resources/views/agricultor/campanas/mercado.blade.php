<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mercado de Campañas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-lg">Estas son las campañas de compra activas de los molinos.</p>

                    <div class="overflow-x-auto mt-6">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Molino</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Campaña</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Progreso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Precio Base</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($campanas as $campana)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $campana->user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $campana->nombre_campana }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-xs">{{ $campana->cantidad_acordada }} / {{ $campana->cantidad_total }} sacos</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">S/ {{ number_format($campana->precio_base, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($campana->lotes_compatibles->isNotEmpty())

                                        {{-- ===== LÍNEA CORREGIDA ===== --}}
                                        <button class="text-indigo-600 hover:text-indigo-900 font-bold"
                                            onclick="openApplyModal('{{ json_encode($campana) }}', '{{ json_encode($campana->lotes_compatibles) }}')">
                                            Participar
                                        </button>
                                        {{-- ============================== --}}

                                        @else
                                        <span class="text-gray-400 italic text-xs">No tienes lotes compatibles</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No hay campañas activas en este momento.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="applyModal" class="fixed top-0 left-0 w-full h-full bg-black bg-opacity-60 hidden justify-center items-center z-50">
        <div class="bg-white dark:bg-gray-800 dark:text-gray-200 rounded-lg shadow-xl p-6 w-full max-w-lg relative">
            <button onclick="openApplyModal()" class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl">&times;</button>

            <h3 class="text-xl font-bold border-b dark:border-gray-600 pb-3">
                Aplicar a Campaña
            </h3>

            <form id="applyForm" method="POST" action="">
                @csrf
                <div class="mt-6 space-y-4">
                    <div>
                        <x-input-label for="lote_id" value="Selecciona tu Lote Compatible" />
                        <select id="lote_id" name="lote_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm"></select>
                    </div>
                    <div>
                        <x-input-label for="cantidad_sacos" value="Cantidad de Sacos a Comprometer" />
                        <x-text-input id="cantidad_sacos" class="block mt-1 w-full" type="number" name="cantidad_sacos" required />
                    </div>
                    <div class="text-xs text-gray-500">
                        <p><strong>Términos:</strong> Al aplicar, aceptas los requisitos de calidad y el precio base de la campaña. El acuerdo final está sujeto a la aprobación del molino.</p>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <x-secondary-button type="button" onclick="closeApplyModal()">
                            Cancelar
                        </x-secondary-button>
                        <x-primary-button class="ms-4">
                            Enviar Aplicación
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>