<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recepción y Pesaje') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- BUSCADOR --}}
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-8 border border-gray-100">
                <div class="p-8 text-center">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Ingreso de Carga</h3>
                    <p class="text-gray-500 mb-6">Busca por DNI del agricultor.</p>
                    
                    <form action="{{ route('caseta.recepcion') }}" method="GET" class="max-w-lg mx-auto flex gap-2">
                        <div class="relative flex-1">
                            <input type="text" name="dni_agricultor" value="{{ request('dni_agricultor') }}" class="block w-full pl-4 pr-3 py-3 border border-gray-300 rounded-lg text-lg font-bold" placeholder="DNI (8 dígitos)" maxlength="8" autofocus required>
                        </div>
                        <button type="submit" class="px-6 py-3 bg-[#5F7F3B] text-white font-bold rounded-lg hover:bg-[#4b662e] shadow">
                            Buscar
                        </button>
                    </form>

                    @if(isset($mensaje))
                        <div class="mt-4 text-yellow-600 font-bold">{{ $mensaje }}</div>
                    @endif
                </div>
            </div>

            {{-- RESULTADOS (Aquí va la tabla que hicimos antes) --}}
            @if(isset($agricultor) && $negociaciones->count() > 0)
                {{-- (Pega aquí la tabla de resultados del paso anterior, es idéntica) --}}
                 <div class="bg-white shadow-md rounded-xl overflow-hidden border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-[#5F7F3B] text-white">
                            <tr>
                                <th class="px-6 py-4 text-left font-bold">ID</th>
                                <th class="px-6 py-4 text-left font-bold">Comprador</th>
                                <th class="px-6 py-4 text-left font-bold">Acuerdo</th>
                                <th class="px-6 py-4 text-center font-bold">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($negociaciones as $negociacion)
                                @php
                                    $propuestaGanadora = $negociacion->propuestas->where('estado', 'aceptada')->first();
                                    $nombreMolino = $propuestaGanadora ? ($propuestaGanadora->user->razon_social ?? $propuestaGanadora->user->name) : 'Desconocido';
                                @endphp
                                <tr class="hover:bg-green-50">
                                    <td class="px-6 py-4 font-bold text-gray-500">#{{ $negociacion->id }}</td>
                                    <td class="px-6 py-4 font-bold">{{ $nombreMolino }}</td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold">{{ $negociacion->cantidad_sacos }}</span> sacos a S/ {{ $negociacion->precio_por_saco }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{-- AQUÍ ESTÁ EL CAMBIO: Agregamos la ruta caseta.evaluar --}}
                                        <a href="{{ route('caseta.evaluar', $negociacion->id) }}" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-md transition transform hover:-translate-y-0.5 focus:outline-none">
                                            <i class="fas fa-balance-scale mr-2"></i> Evaluar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>