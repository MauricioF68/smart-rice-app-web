<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Preventa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6">Modifica los datos de tu preventa. Solo puedes cambiar el precio y las notas.</p>

                    {{-- El formulario apunta a la ruta 'update' y usa el método PUT --}}
                    <form method="POST" action="{{ route('preventas.update', $preventa) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="cantidad_sacos" :value="__('Cantidad de Sacos (Aprox.)')" />
                                <x-text-input id="cantidad_sacos" class="block mt-1 w-full bg-gray-100" type="number" name="cantidad_sacos" :value="$preventa->cantidad_sacos" disabled />
                            </div>

                            <div>
                                <x-input-label for="precio_por_saco" :value="__('Precio Sugerido por Saco (S/)')" />
                                <x-text-input id="precio_por_saco" class="block mt-1 w-full" type="number" name="precio_por_saco" step="0.01" :value="old('precio_por_saco', $preventa->precio_por_saco)" required />
                                <x-input-error :messages="$errors->get('precio_por_saco')" class="mt-2" />
                            </div>

                             <div>
                                <x-input-label for="humedad" :value="__('Humedad (%)')" />
                                <x-text-input id="humedad" class="block mt-1 w-full bg-gray-100" type="number" name="humedad" step="0.1" :value="$preventa->humedad" disabled />
                            </div>

                            <div>
                                <x-input-label for="quebrado" :value="__('Quebrado (%)')" />
                                <x-text-input id="quebrado" class="block mt-1 w-full bg-gray-100" type="number" name="quebrado" step="0.1" :value="$preventa->quebrado" disabled />
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <x-input-label for="notas" :value="__('Notas Adicionales (Opcional)')" />
                            <textarea id="notas" name="notas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notas', $preventa->notas) }}</textarea>
                            <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('preventas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Actualizar Preventa') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>