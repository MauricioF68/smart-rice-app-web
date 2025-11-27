<x-app-layout>
    
    <style>
        :root { --color-primary: #5F7F3B; }
        .table-header { background-color: var(--color-primary); color: white; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        .table-row:hover { background-color: #f9f9f9; }
        .btn-new { background-color: var(--color-primary); color: white; padding: 8px 16px; border-radius: 6px; font-weight: 700; font-size: 0.85rem; border: none; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; }
        .btn-new:hover { background-color: #4b662e; transform: translateY(-1px); }
        .btn-icon { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s; border: none; cursor: pointer; color: white; text-decoration: none; }
        .btn-warning { background-color: #eab308; }
        .btn-danger { background-color: #ef4444; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('status'))
                <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-users-cog mr-3 text-green-600"></i> Operarios de Caseta
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Personal encargado de la recepción y análisis.</p>
                        </div>
                        
                        <a href="{{ route('admin.usuarios-caseta.create') }}" class="btn-new">
                            <i class="fas fa-user-plus"></i> Nuevo Operario
                        </a>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">DNI</th>
                                    <th class="px-6 py-4 text-left font-bold">Nombre Completo</th>
                                    <th class="px-6 py-4 text-left font-bold">Contacto</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($operarios as $operario)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-600 font-bold">
                                        {{ $operario->dni ?? 'S/N' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900">{{ $operario->apellido_paterno }} {{ $operario->apellido_materno }}</div>
                                        <div class="text-sm text-gray-500">{{ $operario->primer_nombre }} {{ $operario->segundo_nombre }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="text-gray-900"><i class="fas fa-envelope mr-1 text-gray-400"></i> {{ $operario->email }}</div>
                                        <div class="text-gray-500"><i class="fas fa-phone mr-1 text-gray-400"></i> {{ $operario->telefono ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2 py-1 text-xs font-bold rounded-full bg-blue-100 text-blue-700 border border-blue-200">
                                            {{ ucfirst($operario->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            {{-- Solo botón Eliminar --}}
                                            <form action="{{ route('admin.usuarios-caseta.destroy', $operario->id) }}" method="POST" 
                                                onsubmit="return confirm('¿Estás seguro de eliminar a este operario? El usuario perderá el acceso inmediatamente.');" 
                                                style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-danger" title="Eliminar Operario">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-user-slash text-4xl mb-3 text-gray-300 block"></i>
                                        No hay operarios registrados.
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