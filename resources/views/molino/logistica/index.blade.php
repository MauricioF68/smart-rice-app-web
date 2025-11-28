<x-app-layout>
    <style>
        .table-header { background-color: #5F7F3B; color: white; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        
        /* Botón Ruta (Azul) */
        .btn-route { background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; display: inline-flex; align-items: center; text-decoration: none; }
        .btn-route:hover { background-color: #2563eb; transform: translateY(-1px); }
        
        /* Botón Detalle (Gris - NUEVO) */
        .btn-detail { background-color: #6b7280; color: white; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; display: inline-flex; align-items: center; text-decoration: none; }
        .btn-detail:hover { background-color: #4b5563; transform: translateY(-1px); }

        .badge-loc { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; padding: 2px 8px; border-radius: 99px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-route mr-3 text-green-600"></i> Programación de Recojos
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Gestiona la logística para recoger el arroz desde los lotes de los agricultores.</p>
                        </div>
                        <a href="{{ route('molino.logistica.configurar') }}" class="text-sm text-gray-500 hover:text-green-600 underline">
                            <i class="fas fa-map-marker-alt"></i> Mi Ubicación
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Agricultor</th>
                                    <th class="px-6 py-4 text-left font-bold">Origen (Lote)</th>
                                    <th class="px-6 py-4 text-left font-bold">Carga</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado Logístico</th>
                                    <th class="px-6 py-4 text-center font-bold">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($porRecoger as $item)
                                    <tr class="hover:bg-gray-50 transition">
                                        
                                        {{-- Agricultor --}}
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $item->user->name }}</div>
                                            <div class="text-xs text-gray-500">Cel: {{ $item->user->telefono ?? '-' }}</div>
                                        </td>

                                        {{-- Origen --}}
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">
                                                <i class="fas fa-map-pin text-red-500 mr-1"></i>
                                                {{ $item->lote->nombre_lote ?? 'Sin nombre' }}
                                            </div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $item->lote->referencia_ubicacion ?? 'Ubicación no especificada' }}
                                            </div>
                                            
                                            @if($item->lote && $item->lote->latitud)
                                                <span class="badge-loc mt-1"><i class="fas fa-satellite mr-1"></i> GPS Activo</span>
                                            @else
                                                <span class="text-xs text-red-500 font-bold mt-1 block">
                                                    <i class="fas fa-exclamation-triangle"></i> Sin GPS
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Carga --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-800">
                                                {{ $item->cantidad_sacos }} sacos
                                            </div>
                                            <div class="text-xs text-gray-500">Pactado</div>
                                        </td>

                                        {{-- ESTADO (LÓGICA ACTUALIZADA) --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($item->recojo)
                                                {{-- YA PROGRAMADO --}}
                                                <span class="px-3 py-1 text-xs font-bold rounded bg-blue-100 text-blue-700 border border-blue-200">
                                                    Programado
                                                </span>
                                                <div class="text-[10px] text-gray-500 mt-1 font-bold">
                                                    Fecha: {{ \Carbon\Carbon::parse($item->recojo->fecha_programada)->format('d/m/Y') }}
                                                </div>
                                            @else
                                                {{-- PENDIENTE --}}
                                                <span class="px-3 py-1 text-xs font-bold rounded bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                    Pendiente de Recojo
                                                </span>
                                            @endif
                                        </td>

                                        {{-- ACCIÓN (LÓGICA ACTUALIZADA) --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($item->recojo)
                                                {{-- Botón GRIS: Ver Detalle (Solo lectura) --}}
                                                <a href="{{ route('molino.logistica.detalle', $item->recojo->id) }}" class="btn-detail">
                                                    <i class="fas fa-eye mr-2"></i> Detalle
                                                </a>
                                            @elseif($item->lote && $item->lote->latitud)
                                                {{-- Botón AZUL: Programar (Formulario) --}}
                                                <a href="{{ route('molino.logistica.programar', $item->id) }}" class="btn-route">
                                                    <i class="fas fa-truck mr-2"></i> Ver Ruta
                                                </a>
                                            @else
                                                <button disabled class="text-gray-400 cursor-not-allowed text-xs font-bold border border-gray-200 px-3 py-2 rounded">
                                                    Sin Ubicación
                                                </button>
                                            @endif
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-road text-4xl mb-3 text-gray-300 block"></i>
                                            <p>No hay recojos pendientes.</p>
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
</x-app-layout>