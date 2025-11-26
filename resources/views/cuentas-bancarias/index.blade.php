<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Cuentas Bancarias') }}
        </h2>
    </x-slot>
    

    <div class="py-12">        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"> 
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('status'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-900 dark:border-green-700 dark:text-green-300" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
                        </div>
                    @endif

                    @if (session('status-error'))
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative dark:bg-red-900 dark:border-red-700 dark:text-red-300" role="alert">
                            <span class="block sm:inline">{{ session('status-error') }}</span>
                        </div>
                    @endif                    

                    <div class="flex justify-end mb-4">                        
                        <a href="{{ route('cuentas-bancarias.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                            Añadir Nueva Cuenta
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Banco</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Titular</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">N° de Cuenta</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($cuentas as $cuenta)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cuenta->banco_nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cuenta->titular_nombres }} {{ $cuenta->titular_apellidos }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $cuenta->numero_cuenta }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">

                                        @if ($cuenta->is_primary)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                                Principal
                                            </span>
                                        @else
                                            <form action="{{ route('cuentas-bancarias.setPrimary', $cuenta) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                                    Marcar como Principal
                                                </button>
                                            </form>
                                        @endif
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 ml-4">Editar</a>
                                        <form action="{{ route('cuentas-bancarias.destroy', $cuenta) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta cuenta bancaria?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </form>

                                    </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                            No tienes cuentas bancarias registradas.
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