<x-app-layout>
    
    {{-- 1. ESTILOS (ADN SmartRice) --}}
    <style>
        :root { --color-primary: #5F7F3B; }

        /* Cabecera de Tabla */
        .table-header { 
            background-color: var(--color-primary); 
            color: white; 
            text-transform: uppercase; 
            font-size: 0.85rem; 
            letter-spacing: 0.5px; 
        }
        
        /* Filas */
        .table-row:hover { background-color: #f9f9f9; }

        /* Botón Principal (Nuevo) */
        .btn-new { 
            background-color: var(--color-primary); 
            color: white; 
            padding: 8px 16px; 
            border-radius: 6px; 
            font-weight: 700; 
            font-size: 0.85rem; 
            border: none; 
            cursor: pointer; 
            transition: all 0.2s; 
            display: inline-flex; 
            align-items: center; 
            gap: 5px; 
            text-decoration: none;
        }
        .btn-new:hover { background-color: #4b662e; transform: translateY(-1px); }

        /* Botones de Acción (Iconos) */
        .btn-icon {
            width: 32px; height: 32px; border-radius: 6px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; border: none; cursor: pointer; color: white;
            text-decoration: none;
        }
        .btn-warning { background-color: #eab308; } /* Amarillo para editar */
        .btn-warning:hover { background-color: #ca8a04; }
        
        .btn-danger { background-color: #ef4444; } /* Rojo para eliminar */
        .btn-danger:hover { background-color: #dc2626; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TARJETA PRINCIPAL (Diseño SmartRice) --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    {{-- ENCABEZADO DE LA SECCIÓN --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-seedling mr-3 text-green-600"></i> Gestionar Tipos de Arroz
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Variedades de arroz disponibles en la plataforma.</p>
                        </div>
                        
                        {{-- Botón "Añadir Nuevo" con estilo SmartRice --}}
                        <a href="{{ route('admin.tipos-arroz.create') }}" class="btn-new">
                            <i class="fas fa-plus"></i> Añadir Nuevo Tipo
                        </a>
                    </div>

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            
                            {{-- Cabecera Verde --}}
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold w-20">ID</th>
                                    <th class="px-6 py-4 text-left font-bold">Nombre de la Variedad</th>
                                    <th class="px-6 py-4 text-center font-bold w-40">Acciones</th>
                                </tr>
                            </thead>

                            {{-- Cuerpo de la Tabla --}}
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($tiposArroz as $tipo)
                                    <tr class="table-row transition-colors">
                                        
                                        {{-- ID --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-mono">
                                            #{{ $tipo->id }}
                                        </td>
                                        
                                        {{-- Nombre --}}
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $tipo->nombre }}
                                        </td>
                                        
                                        {{-- Acciones (Botones de Iconos) --}}
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center items-center gap-2">
                                                
                                                {{-- Botón Editar --}}
                                                <a href="{{ route('admin.tipos-arroz.edit', $tipo) }}" class="btn-icon btn-warning" title="Editar">
                                                    <i class="fas fa-pen"></i>
                                                </a>

                                                {{-- Formulario Eliminar --}}
                                                <form action="{{ route('admin.tipos-arroz.destroy', $tipo) }}" method="POST" class="inline" onsubmit="return confirm('¿Estás seguro de eliminar este tipo de arroz?');">
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
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                                            <i class="fas fa-leaf text-4xl mb-3 text-gray-300 block"></i>
                                            <p>No hay tipos de arroz registrados.</p>
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