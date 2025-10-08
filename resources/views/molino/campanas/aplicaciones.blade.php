<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Aplicaciones para la Campaña: "{{ $campana->nombre_campana }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- MENSAJE DE ESTADO --}}
                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Aplicaciones Pendientes de Aprobación
                    </h3>

                    @if ($aplicaciones->isEmpty())
                        <p class="text-center text-gray-500">No hay aplicaciones pendientes para esta campaña.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-200">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Agricultor</th>
                                        <th class="py-2 px-4 border-b">Lote</th>
                                        <th class="py-2 px-4 border-b">Sacos Ofrecidos</th>
                                        <th class="py-2 px-4 border-b">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aplicaciones as $aplicacion)
                                        <tr class="hover:bg-gray-100">
                                            <td class="py-2 px-4 border-b">{{ $aplicacion->user->name }}</td>
                                            <td class="py-2 px-4 border-b">{{ $aplicacion->lote->nombre_lote }}</td>
                                            <td class="py-2 px-4 border-b text-center">{{ $aplicacion->cantidad_comprometida }}</td>
                                            <td class="py-2 px-4 border-b text-center">
                                                {{-- Formularios de Acciones --}}
                                                <div class="flex justify-center space-x-2">
                                                    {{-- BOTÓN APROBAR --}}
                                                    <form method="POST" action="{{ route('aplicaciones.aprobar', $aplicacion) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Aprobar</button>
                                                    </form>

                                                    {{-- BOTÓN RECHAZAR --}}
                                                    <form method="POST" action="{{ route('aplicaciones.rechazar', $aplicacion) }}">
                                                        @csrf
                                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Rechazar</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>