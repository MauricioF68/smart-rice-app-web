<x-app-layout>
    
    <style>
        /* --- ESTILOS DE LA TABLA --- */
        .table-header {
            background-color: var(--color-primary); /* Verde */
            color: white;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .table-row:hover {
            background-color: #f9f9f9;
        }

        /* --- ESTILOS DEL BOTÓN "NUEVA PREVENTA" --- */
        .btn-new {
            display: inline-flex;
            align-items: center;
            background-color: var(--color-primary); /* Verde Corporativo */
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .btn-new:hover {
            background-color: #4b662e; /* Verde más oscuro al pasar mouse */
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }

        /* --- BOTONES DE ACCIÓN (ICONOS) --- */
        .btn-icon {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 0.9rem;
            margin-left: 5px;
        }

        .btn-icon-blue { background-color: #3b82f6; } 
        .btn-icon-blue:hover { background-color: #2563eb; transform: translateY(-2px); }

        .btn-icon-yellow { background-color: var(--color-secondary); } 
        .btn-icon-yellow:hover { background-color: #9a8235; transform: translateY(-2px); }

        .btn-icon-red { background-color: #ef4444; } 
        .btn-icon-red:hover { background-color: #dc2626; transform: translateY(-2px); }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">
                    
                    {{-- ENCABEZADO CON EL BOTÓN --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Mis Preventas</h2>
                            <p class="text-gray-500 text-sm mt-1">Gestiona tus ofertas de arroz.</p>
                        </div>
                        
                        <a href="{{ route('preventas.create') }}" class="btn-new">
                            <i class="fas fa-plus mr-2"></i> Crear Nueva Preventa
                        </a>
                    </div>

                    {{-- MENSAJE DE ÉXITO --}}
                    @if (session('status'))
                        <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 border-l-4 border-green-500 flex items-center shadow-sm">
                            <i class="fas fa-check-circle mr-3 text-lg"></i>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">ID</th>
                                    <th class="px-6 py-4 text-left font-bold">Fecha</th>
                                    <th class="px-6 py-4 text-left font-bold">Sacos</th>
                                    <th class="px-6 py-4 text-left font-bold">Precio / Saco</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($preventas as $preventa)
                                <tr class="table-row transition-colors duration-200">
                                    <td class="px-6 py-4 font-medium text-gray-900">#PV-{{ $preventa->id }}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                        {{ $preventa->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800">{{ $preventa->cantidad_sacos }}</span>
                                        <span class="text-xs text-gray-500">unid.</span>
                                    </td>
                                    <td class="px-6 py-4 text-gray-800 font-semibold">
                                        S/ {{ number_format($preventa->precio_por_saco, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <x-status-badge :estado="$preventa->estado" />
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center">
                                            <a href="{{ route('preventas.show', $preventa) }}" class="btn-icon btn-icon-blue" title="Ver Detalle">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($preventa->propuestas->isEmpty())
                                                <a href="{{ route('preventas.edit', $preventa) }}" class="btn-icon btn-icon-yellow" title="Editar">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                <form action="{{ route('preventas.destroy', $preventa) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Borrar preventa?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon btn-icon-red" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="ml-2 text-gray-400 text-xs" title="Bloqueado por propuestas">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-seedling text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-500">Aún no hay preventas.</p>
                                            
                                            <a href="{{ route('preventas.create') }}" class="btn-new mt-4">
                                                Crear la primera
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($preventas, 'links'))
                        <div class="mt-4">
                            {{ $preventas->links() }}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>