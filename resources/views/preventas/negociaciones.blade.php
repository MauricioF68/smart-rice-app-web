<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Negociaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold text-yellow-600 dark:text-yellow-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        Propuestas Pendientes de Decisión
                    </h3>
                    @forelse ($propuestasPendientes as $propuesta)
                        <div class="p-4 mb-4 border rounded-lg dark:border-gray-700 flex justify-between items-center">
                            <div>
                                <p class="font-semibold">
                                    El molino <span class="text-blue-600 dark:text-blue-400">{{ $propuesta->user->name }}</span> ha hecho una oferta por tu preventa <span class="text-gray-500">PV-{{ $propuesta->preventa->id }}</span>
                                </p>
                                <p class="text-sm mt-2">
                                    Tu pedías: <span class="line-through">{{ $propuesta->preventa->cantidad_sacos }} sacos a S/ {{ number_format($propuesta->preventa->precio_por_saco, 2) }}</span>
                                </p>
                                <p class="text-md font-bold text-green-600 dark:text-green-400">
                                    Ellos ofrecen: {{ $propuesta->cantidad_sacos_propuesta }} sacos a S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}
                                </p>
                            </div>
                            <div class="flex space-x-2">
                                <form action="{{ route('propuestas.accept', $propuesta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de aceptar esta propuesta? Se cerrará el trato.');">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 text-sm font-bold">Aceptar</button>
                                </form>
                                <form action="{{ route('propuestas.reject', $propuesta) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 text-sm font-bold">Rechazar</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No tienes propuestas pendientes de revisión.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold text-green-600 dark:text-green-400 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        Historial de Tratos Acordados
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="py-2 text-left text-sm">Preventa ID</th>
                                    <th class="py-2 text-left text-sm">Molino</th>
                                    <th class="py-2 text-left text-sm">Cantidad Acordada</th>
                                    <th class="py-2 text-left text-sm">Precio Acordado</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($propuestasAcordadas as $propuesta)
                                <tr class="border-t dark:border-gray-700">
                                    <td class="py-2">PV-{{ $propuesta->preventa->id }}</td>
                                    <td class="py-2">{{ $propuesta->user->name }}</td>
                                    <td class="py-2">{{ $propuesta->cantidad_sacos_propuesta }} sacos</td>
                                    <td class="py-2">S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-4 text-center text-gray-500">Aún no has cerrado ningún trato.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>