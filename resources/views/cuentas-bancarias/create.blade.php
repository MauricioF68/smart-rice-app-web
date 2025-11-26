<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Añadir Nueva Cuenta Bancaria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('cuentas-bancarias.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="banco_nombre" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Banco</label>
                            <select name="banco_nombre" id="banco_nombre" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                <option value="">-- Selecciona un banco --</option>
                                @foreach ($bancos as $banco)
                                    <option value="{{ $banco }}">{{ $banco }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="tipo_cuenta" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Tipo de Cuenta</label>
                            <select name="tipo_cuenta" id="tipo_cuenta" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                <option value="">-- Selecciona un tipo --</option>
                                @foreach ($tipos_cuenta as $tipo)
                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="titular_nombres" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nombres del Titular</label>
                            <input type="text" name="titular_nombres" id="titular_nombres" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="titular_apellidos" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Apellidos del Titular</label>
                            <input type="text" name="titular_apellidos" id="titular_apellidos" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="numero_cuenta" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Número de Cuenta</label>
                            <input type="text" name="numero_cuenta" id="numero_cuenta" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>

                        <div class="mb-4">
                            <label for="cci" class="block font-medium text-sm text-gray-700 dark:text-gray-300">CCI (Cuenta Interbancaria) - Opcional</label>
                            <input type="text" name="cci" id="cci" class="block mt-1 w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cuentas-bancarias.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md">
                                Cancelar
                            </a>
                            <button type="submit" class="ms-4 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                                Guardar Cuenta
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>