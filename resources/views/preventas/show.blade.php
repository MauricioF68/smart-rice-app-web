<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestionar Negociación: Preventa PV-' . $preventa->id) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Resumen de la Preventa --}}
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-bold text-lg mb-2">Tu Oferta Original</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                            <div>
                                <p class="text-sm text-gray-500">Sacos Ofertados</p>
                                <p class="text-xl font-bold">{{ $preventa->cantidad_sacos }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Precio Sugerido</p>
                                <p class="text-xl font-bold">S/ {{ number_format($preventa->precio_por_saco, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Humedad</p>
                                <p class="text-xl font-bold">{{ $preventa->humedad }}%</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Estado</p>
                                <p class="text-xl font-bold capitalize">{{ $preventa->estado }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Lista de Propuestas Recibidas --}}
                    <div>
                        <h3 class="font-bold text-lg mb-4">Propuestas de los Molinos</h3>
                        <div class="space-y-4">
                            @forelse ($preventa->propuestas as $propuesta)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex justify-between items-center">
                                {{-- Detalles de la propuesta --}}
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $propuesta->user->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Propuesta: <strong>{{ $propuesta->cantidad_sacos_propuesta }}</strong> sacos a
                                        <strong>S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}</strong> c/u
                                    </p>
                                </div>

                                {{-- Botones de Acción (solo si la preventa sigue activa) --}}
                                @if ($preventa->estado === 'activa')
                                <div class="flex items-center">
                                    <form action="{{ route('propuestas.reject', $propuesta) }}" method="POST" class="inline">
                                        @csrf
                                        <x-secondary-button type="submit">Rechazar</x-secondary-button>
                                    </form>

                                    <form action="{{ route('propuestas.accept', $propuesta) }}" method="POST" class="inline ml-2" onsubmit="return confirm('¿Estás seguro de que deseas cerrar el trato con este molino? Esta acción es final.');">
                                        @csrf
                                        <x-primary-button type="submit">Cerrar Trato</x-primary-button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 italic">Esta preventa aún no ha recibido ninguna propuesta.</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>