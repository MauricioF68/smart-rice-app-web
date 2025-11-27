<x-app-layout>
    
    {{-- ESTILOS SMARTRICE --}}
    <style>
        :root { --color-primary: #5F7F3B; }
        .table-header { background-color: var(--color-primary); color: white; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .table-row:hover { background-color: #f9f9f9; }
        
        /* Botón Nuevo */
        .btn-new { background-color: var(--color-primary); color: white; padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-new:hover { background-color: #4b662e; transform: translateY(-1px); }

        /* Botones Acción */
        .btn-icon { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; color: white; text-decoration: none; }
        .btn-warning { background-color: #eab308; }
        .btn-danger { background-color: #ef4444; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- MENSAJES DE ÉXITO --}}
            @if (session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    {{-- ENCABEZADO --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-warehouse mr-3 text-green-600"></i> Gestión de Casetas
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Administra los puntos de control y pesaje.</p>
                        </div>
                        
                        <a href="{{ route('admin.casetas.create') }}" class="btn-new">
                            <i class="fas fa-plus"></i> Nueva Caseta
                        </a>
                    </div>

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Código</th>
                                    <th class="px-6 py-4 text-left font-bold">Nombre de Caseta</th>
                                    <th class="px-6 py-4 text-left font-bold">Ubicación</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($casetas as $caseta)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600 font-bold">
                                        {{ $caseta->codigo_unico }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $caseta->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        <i class="fas fa-map-marker-alt text-red-400 mr-1"></i> {{ $caseta->ubicacion ?? 'No registrada' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if($caseta->activa)
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 border border-green-200">Activa</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">Inactiva</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            {{-- Editar (Aún no funcional, solo visual) --}}
                                            <a href="{{ route('admin.casetas.edit', $caseta->id) }}" class="btn-icon btn-warning" title="Editar">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            {{-- Eliminar (Aún no funcional, solo visual) --}}
                                            <form action="{{ route('admin.casetas.destroy', $caseta->id) }}" method="POST" 
                                                onsubmit="return confirm('¿Estás seguro de eliminar esta caseta? Esta acción no se puede deshacer.');" 
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-danger" title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-warehouse text-4xl mb-3 text-gray-300 block"></i>
                                        No hay casetas registradas.
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