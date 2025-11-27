<x-app-layout>
    
    {{-- ESTILOS --}}
    <style>
        .table-header { background-color: #5F7F3B; color: white; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .btn-pay { background-color: #3b82f6; color: white; padding: 6px 12px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; display: inline-flex; align-items: center; text-decoration: none; }
        .btn-pay:hover { background-color: #2563eb; transform: translateY(-1px); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-truck-loading mr-3 text-green-600"></i> Lotes Recibidos y Por Pagar
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            Listado de lotes que ya pasaron por caseta y están listas para hacer el pago respectivo a sus numeros de cuenta.
                        </p>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Agricultor</th>
                                    <th class="px-6 py-4 text-left font-bold">Recepción (Caseta)</th>
                                    <th class="px-6 py-4 text-left font-bold">Datos Reales</th>
                                    <th class="px-6 py-4 text-left font-bold">Total a Pagar</th>
                                    <th class="px-6 py-4 text-center font-bold">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($porPagar as $item)
                                    {{-- CÁLCULOS --}}
                                    @php
                                        // Usamos los datos REALES de la caseta para calcular el pago
                                        $sacosReales = $item->analisisCalidad->cantidad_sacos_real ?? 0;
                                        $pesoReal = $item->analisisCalidad->peso_real_sacos ?? 0;
                                        $precioPactado = $item->precio_por_saco;
                                        $totalPagar = $sacosReales * $precioPactado;
                                    @endphp

                                    <tr class="hover:bg-gray-50 transition">
                                        {{-- Agricultor --}}
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-900">{{ $item->user->name }}</div>
                                            <div class="text-xs text-gray-500">DNI: {{ $item->user->dni }}</div>
                                        </td>

                                        {{-- Datos Caseta --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-700">
                                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>
                                                {{ $item->analisisCalidad->created_at->format('d/m/Y h:i A') }}
                                            </div>
                                            <div class="text-xs text-green-600 font-bold mt-1">
                                                Verificado en Caseta
                                            </div>
                                        </td>

                                        {{-- Datos Reales --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-gray-800">
                                                {{ $sacosReales }} sacos
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Peso Neto: {{ $pesoReal }} kg
                                            </div>
                                        </td>

                                        {{-- Total --}}
                                        <td class="px-6 py-4">
                                            <div class="text-lg font-bold text-green-700">
                                                S/ {{ number_format($totalPagar, 2) }}
                                            </div>
                                            <div class="text-xs text-gray-400">
                                                ({{ $sacosReales }} x S/ {{ $precioPactado }})
                                            </div>
                                        </td>

                                        {{-- Botón Pagar --}}
                                        <td class="px-6 py-4 text-center">
                                            {{-- Aquí pondremos la ruta al formulario de pago pronto --}}
                                            <a href="{{ route('molino.pagos.create', $item->id) }}" class="btn-pay">
                                                <i class="fas fa-hand-holding-usd mr-2"></i> Pagar
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-check-circle text-4xl mb-3 text-green-200 block"></i>
                                            <p>No tienes pagos pendientes.</p>
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