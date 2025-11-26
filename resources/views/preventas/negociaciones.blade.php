<x-app-layout>
    
    <style>
        /* --- TARJETAS DE NEGOCIACIÓN --- */
        .negotiation-card {
            background: white;
            border: 1px solid #e5e7eb;
            border-left: 5px solid var(--color-secondary); /* Borde Amarillo */
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s;
        }

        .negotiation-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }

        /* --- COMPARATIVA DE PRECIOS --- */
        .comparison-box {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .original-price {
            font-size: 0.85rem;
            color: #9ca3af;
            text-decoration: line-through;
        }

        .offered-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--color-primary); /* Precio oferta en Verde */
        }

        /* --- TABLA HISTORIAL --- */
        .table-header {
            background-color: var(--color-primary);
            color: white;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }

        /* --- BOTONES --- */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }

        .btn-accept { background-color: var(--color-primary); color: white; }
        .btn-accept:hover { background-color: #4b662e; transform: translateY(-1px); }

        .btn-reject { background-color: white; color: #ef4444; border: 1px solid #fecaca; }
        .btn-reject:hover { background-color: #fee2e2; }

        /* Avatar del Molino */
        .molino-avatar {
            width: 40px; height: 40px; background-color: #fef3c7; color: #d97706;
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: bold; font-size: 1.1rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div>
                <div class="flex items-center mb-4">
                    <div class="bg-yellow-100 p-2 rounded-full mr-3 text-yellow-600">
                        <i class="fas fa-bell text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">
                        Propuestas Pendientes de Decisión
                    </h3>
                </div>

                @forelse ($propuestasPendientes as $propuesta)
                    <div class="negotiation-card">
                        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                            
                            <div class="flex items-start gap-4 flex-1">
                                <div class="molino-avatar">
                                    {{ substr($propuesta->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-lg text-gray-800">{{ $propuesta->user->name }}</h4>
                                    <p class="text-sm text-gray-500">
                                        Oferta para tu preventa <span class="font-mono bg-gray-100 px-1 rounded">#PV-{{ $propuesta->preventa->id }}</span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="far fa-clock"></i> Recibida el {{ $propuesta->created_at->format('d/m/Y h:i A') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-6 w-full md:w-auto">
                                <div class="comparison-box flex-1 md:flex-none text-center">
                                    <span class="text-xs text-gray-500 uppercase font-bold">Cantidad</span>
                                    <span class="original-price">{{ $propuesta->preventa->cantidad_sacos }} sacos</span>
                                    <span class="font-bold text-gray-800">{{ $propuesta->cantidad_sacos_propuesta }} sacos</span>
                                </div>

                                <div class="comparison-box flex-1 md:flex-none text-center bg-green-50 border border-green-100">
                                    <span class="text-xs text-green-700 uppercase font-bold">Precio</span>
                                    <span class="original-price">S/ {{ number_format($propuesta->preventa->precio_por_saco, 2) }}</span>
                                    <span class="offered-price">S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('propuestas.reject', $propuesta) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-reject" title="Rechazar">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </form>

                                <form action="{{ route('propuestas.accept', $propuesta) }}" method="POST" onsubmit="return confirm('¿Estás seguro de aceptar esta propuesta? Se cerrará el trato y se generará la orden de venta.');">
                                    @csrf
                                    <button type="submit" class="btn-action btn-accept">
                                        Aceptar Oferta <i class="fas fa-check ml-2"></i>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg p-8 text-center border border-gray-100 shadow-sm">
                        <i class="fas fa-check-double text-4xl text-gray-200 mb-3"></i>
                        <p class="text-gray-500">Estás al día. No tienes propuestas pendientes de revisión.</p>
                    </div>
                @endforelse
            </div>


            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-handshake mr-2 text-green-600"></i> Historial de Tratos Cerrados
                    </h3>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-3 text-left">Preventa</th>
                                    <th class="px-6 py-3 text-left">Molino</th>
                                    <th class="px-6 py-3 text-left">Cantidad Acordada</th>
                                    <th class="px-6 py-3 text-left">Precio Final</th>
                                    <th class="px-6 py-3 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($propuestasAcordadas as $propuesta)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            #PV-{{ $propuesta->preventa->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $propuesta->user->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-700">
                                            {{ $propuesta->cantidad_sacos_propuesta }} sacos
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-green-700">
                                            S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="px-2 py-1 text-xs font-bold text-green-700 bg-green-100 rounded-full">
                                                Completado
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                            Aún no has cerrado ningún trato.
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