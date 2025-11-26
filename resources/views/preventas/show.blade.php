<x-app-layout>
    
    <style>
        /* Bloque de Resumen */
        .summary-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }
        
        /* Pequeña barra decorativa verde a la izquierda */
        .summary-card::before {
            content: '';
            position: absolute; left: 0; top: 0; bottom: 0;
            width: 6px; background-color: var(--color-primary);
        }

        .stat-label { font-size: 0.85rem; color: #6b7280; text-transform: uppercase; font-weight: 600; }
        .stat-value { font-size: 1.5rem; font-weight: 800; color: #1f2937; margin-top: 5px; }

        /* Tarjetas de Propuesta */
        .proposal-card {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .proposal-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-color: var(--color-secondary); /* Borde amarillo al pasar mouse */
        }

        .molino-name { font-weight: 700; font-size: 1.1rem; color: #1f2937; }
        .proposal-details { color: #4b5563; font-size: 0.95rem; margin-top: 4px; }
        
        /* Botones de Acción */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.9rem;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }

        .btn-reject {
            background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca;
        }
        .btn-reject:hover { background-color: #fecaca; }

        .btn-accept {
            background-color: var(--color-primary); color: white;
            box-shadow: 0 2px 4px rgba(95, 127, 59, 0.3);
        }
        .btn-accept:hover { background-color: #4b662e; transform: translateY(-1px); }

    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100 p-8">

                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Gestionar Negociación</h2>
                        <p class="text-gray-500">Preventa #PV-{{ $preventa->id }}</p>
                    </div>
                    <a href="{{ route('preventas.index') }}" class="text-gray-500 hover:text-gray-700 font-medium flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                </div>

                <div class="summary-card">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-gray-700">
                            <i class="fas fa-box-open mr-2 text-green-600"></i> Tu Oferta Original
                        </h3>
                        <x-status-badge :estado="$preventa->estado" />
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div>
                            <p class="stat-label">Sacos Ofertados</p>
                            <p class="stat-value">{{ $preventa->cantidad_sacos }}</p>
                        </div>
                        <div>
                            <p class="stat-label">Precio Sugerido</p>
                            <p class="stat-value text-green-700">S/ {{ number_format($preventa->precio_por_saco, 2) }}</p>
                        </div>
                        <div>
                            <p class="stat-label">Humedad</p>
                            <p class="stat-value text-gray-600">{{ $preventa->humedad }}%</p>
                        </div>
                        <div>
                            <p class="stat-label">Quebrado</p>
                            <p class="stat-value text-gray-600">{{ $preventa->quebrado }}%</p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="font-bold text-xl mb-6 flex items-center">
                        <i class="fas fa-comments-dollar mr-2 text-yellow-600"></i>
                        Propuestas Recibidas ({{ $preventa->propuestas->count() }})
                    </h3>

                    <div class="space-y-4">
                        @forelse ($preventa->propuestas as $propuesta)
                        
                        <div class="proposal-card">
                            
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 text-xl font-bold">
                                    {{ substr($propuesta->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="molino-name">{{ $propuesta->user->name }} ({{ $propuesta->user->razon_social ?? 'Molino' }})</p>
                                    <div class="proposal-details">
                                        Ofrece comprar <strong>{{ $propuesta->cantidad_sacos_propuesta }}</strong> sacos a
                                        <span class="text-green-700 font-bold bg-green-50 px-2 py-1 rounded">
                                            S/ {{ number_format($propuesta->precio_por_saco_propuesta, 2) }}
                                        </span> cada uno.
                                    </div>
                                    <p class="text-xs text-gray-400 mt-1">
                                        <i class="far fa-clock"></i> Recibida el {{ $propuesta->created_at->format('d/m/Y h:i A') }}
                                    </p>
                                </div>
                            </div>

                            @if ($preventa->estado === 'activa' || $preventa->estado === 'publicada')
                                <div class="flex items-center gap-3">
                                    <form action="{{ route('propuestas.reject', $propuesta) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-action btn-reject" title="Rechazar Oferta">
                                            <i class="fas fa-times mr-2"></i> Rechazar
                                        </button>
                                    </form>

                                    <form action="{{ route('propuestas.accept', $propuesta) }}" method="POST" onsubmit="return confirm('¡Atención!\n\nAl cerrar el trato, se generará una orden de venta y la preventa finalizará.\n¿Estás seguro?');">
                                        @csrf
                                        <button type="submit" class="btn-action btn-accept">
                                            <i class="fas fa-check-circle mr-2"></i> Cerrar Trato
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="px-4 py-2 rounded-lg font-bold text-sm 
                                    {{ $propuesta->estado === 'aceptada' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                    {{ ucfirst($propuesta->estado) }}
                                </div>
                            @endif

                        </div>

                        @empty
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                                <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">Aún no has recibido ofertas.</p>
                                <p class="text-sm text-gray-400">Paciencia, los molinos pronto verán tu publicación.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>