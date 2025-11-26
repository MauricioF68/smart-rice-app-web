<x-app-layout>
    
    <style>
        .table-header {
            background-color: var(--color-primary); color: white;
            text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px;
        }
        .table-row:hover { background-color: #f9f9f9; }

        .btn-new {
            display: inline-flex; align-items: center;
            background-color: var(--color-primary); color: white;
            padding: 10px 20px; border-radius: 8px; font-weight: 700;
            text-decoration: none; transition: all 0.3s;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .btn-new:hover { background-color: #4b662e; transform: translateY(-2px); }

        /* Badge Principal */
        .badge-primary {
            background-color: #dcfce7; color: #166534;
            padding: 5px 12px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 800; border: 1px solid #bbf7d0;
            display: inline-flex; align-items: center; gap: 5px;
        }

        /* Botones de Acción */
        .btn-icon {
            width: 35px; height: 35px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            transition: all 0.2s; border: none; cursor: pointer;
            font-size: 0.9rem; margin-left: 5px;
        }
        
        .btn-star {
            background-color: #fff; border: 1px solid #e5e7eb; color: #9ca3af;
        }
        .btn-star:hover {
            border-color: #fbbf24; color: #d97706; background-color: #fffbeb;
        }

        .btn-delete {
            background-color: #fee2e2; color: #b91c1c;
        }
        .btn-delete:hover { background-color: #fecaca; }

    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-6">

                    {{-- HEADER --}}
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                                <i class="fas fa-university mr-3 text-gray-400"></i> Mis Cuentas Bancarias
                            </h2>
                            <p class="text-gray-500 text-sm mt-1">Administra las cuentas donde recibirás los pagos de tus ventas.</p>
                        </div>
                        
                        <a href="{{ route('cuentas-bancarias.create') }}" class="btn-new">
                            <i class="fas fa-plus mr-2"></i> Añadir Nueva Cuenta
                        </a>
                    </div>

                    {{-- ALERTAS --}}
                    @if (session('status'))
                        <div class="mb-4 p-4 rounded-lg bg-green-50 text-green-800 border-l-4 border-green-500 flex items-center shadow-sm">
                            <i class="fas fa-check-circle mr-3 text-lg"></i>
                            <span class="font-medium">{{ session('status') }}</span>
                        </div>
                    @endif

                    @if (session('status-error'))
                        <div class="mb-4 p-4 rounded-lg bg-red-50 text-red-800 border-l-4 border-red-500 flex items-center shadow-sm">
                            <i class="fas fa-exclamation-circle mr-3 text-lg"></i>
                            <span class="font-medium">{{ session('status-error') }}</span>
                        </div>
                    @endif

                    {{-- TABLA --}}
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold">Banco</th>
                                    <th class="px-6 py-4 text-left font-bold">Titular</th>
                                    <th class="px-6 py-4 text-left font-bold">N° de Cuenta</th>
                                    <th class="px-6 py-4 text-center font-bold">Estado</th>
                                    <th class="px-6 py-4 text-center font-bold">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($cuentas as $cuenta)
                                <tr class="table-row transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                        {{ $cuenta->banco_nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                        {{ $cuenta->titular_nombres }} {{ $cuenta->titular_apellidos }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-700">
                                        {{ $cuenta->numero_cuenta }}
                                    </td>
                                    
                                    {{-- ESTADO --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        @if ($cuenta->is_primary)
                                            <span class="badge-primary">
                                                <i class="fas fa-check"></i> Principal
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Secundaria</span>
                                        @endif
                                    </td>

                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center items-center">
                                            
                                            @if (!$cuenta->is_primary)
                                                <form action="{{ route('cuentas-bancarias.setPrimary', $cuenta) }}" method="POST" class="inline-block" title="Establecer como cuenta principal">
                                                    @csrf
                                                    <button type="submit" class="btn-icon btn-star">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('cuentas-bancarias.destroy', $cuenta) }}" method="POST" class="inline-block" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon btn-delete ml-2" title="Eliminar cuenta">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-gray-400">
                                            <i class="fas fa-wallet text-5xl mb-3 text-gray-300"></i>
                                            <p class="text-lg font-medium text-gray-500">No tienes cuentas registradas.</p>
                                            <p class="text-sm mb-4">Añade una para poder recibir pagos.</p>
                                        </div>
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