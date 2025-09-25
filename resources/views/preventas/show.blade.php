<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{-- Usamos el ID para un título dinámico --}}
            {{ __('Detalle de la Preventa PV-' . $preventa->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Botón para volver al listado --}}
                    <a href="{{ route('preventas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 mb-6 inline-block">
                        <i class="fas fa-arrow-left"></i>
                        Volver al listado
                    </a>

                    {{-- Contenido del Detalle --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Columna de Datos de la Oferta --}}
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-bold text-lg">Datos de la Oferta</h3>
                                <p><strong>Cantidad de Sacos:</strong> {{ $preventa->cantidad_sacos }}</p>
                                <p><strong>Precio por Saco:</strong> S/ {{ number_format($preventa->precio_por_saco, 2) }}</p>
                                <p><strong>Estado:</strong> <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ ucfirst($preventa->estado) }}</span></p>
                                <p><strong>Fecha de Publicación:</strong> {{ $preventa->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg">Parámetros de Calidad</h3>
                                <p><strong>Humedad:</strong> {{ $preventa->humedad }}%</p>
                                <p><strong>Quebrado:</strong> {{ $preventa->quebrado }}%</p>
                            </div>
                            @if($preventa->notas)
                            <div>
                                <h3 class="font-bold text-lg">Notas Adicionales</h3>
                                <p class="whitespace-pre-wrap">{{ $preventa->notas }}</p>
                            </div>
                            @endif
                        </div>

                        {{-- Columna de Propuestas (Placeholder) --}}
                        <div class="space-y-4">
                             <h3 class="font-bold text-lg">Propuestas Recibidas</h3>
                             <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4">
                                <p class="text-gray-500 italic">Aquí se mostrará la lista de propuestas de los molinos cuando implementemos esa funcionalidad.</p>
                             </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>