<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nueva Campaña') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('campanas.store') }}">
                        @csrf

                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Datos Generales</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nombre_campana" value="Nombre de la Campaña" />
                                <x-text-input id="nombre_campana" class="block mt-1 w-full" type="text" name="nombre_campana" :value="old('nombre_campana')" required />
                            </div>
                            <div>
                                <x-input-label for="tipo_arroz_id" value="Variedad de Arroz" />
                                <select id="tipo_arroz_id" name="tipo_arroz_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">-- Selecciona una variedad --</option>
                                    @foreach ($tiposArroz as $tipo)
                                    <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="cantidad_total" value="Cantidad Total de Sacos Buscados" />
                                <x-text-input id="cantidad_total" class="block mt-1 w-full" type="number" name="cantidad_total" :value="old('cantidad_total')" required />
                            </div>
                            <div>
                                <x-input-label for="precio_base" value="Precio Base por Saco (S/)" />
                                <x-text-input id="precio_base" class="block mt-1 w-full" type="number" name="precio_base" step="0.01" :value="old('precio_base')" required />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Reglas por Agricultor (Opcional) </h3>
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="min_sacos_por_agricultor" value="Mínimo de Sacos por Agricultor" />
                                <x-text-input id="min_sacos_por_agricultor" class="block mt-1 w-full" type="number" name="min_sacos_por_agricultor" :value="old('min_sacos_por_agricultor')" />
                            </div>
                             <div>
                                <x-input-label for="max_sacos_por_agricultor" value="Máximo de Sacos por Agricultor" />
                                <x-text-input id="max_sacos_por_agricultor" class="block mt-1 w-full" type="number" name="max_sacos_por_agricultor" :value="old('max_sacos_por_agricultor')" />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Reglas de Calidad (Opcional)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <div>
                                <x-input-label for="humedad_min" value="Humedad Mín. (%)" />
                                <x-text-input id="humedad_min" class="block mt-1 w-full" type="number" step="0.1" name="humedad_min" :value="old('humedad_min')" />
                            </div>
                            <div>
                                <x-input-label for="humedad_max" value="Humedad Máx. (%)" />
                                <x-text-input id="humedad_max" class="block mt-1 w-full" type="number" step="0.1" name="humedad_max" :value="old('humedad_max')" />
                            </div>
                            <div>
                                <x-input-label for="quebrado_min" value="Quebrado Mín. (%)" />
                                <x-text-input id="quebrado_min" class="block mt-1 w-full" type="number" step="0.1" name="quebrado_min" :value="old('quebrado_min')" />
                            </div>
                            <div>
                                <x-input-label for="quebrado_max" value="Quebrado Máx. (%)" />
                                <x-text-input id="quebrado_max" class="block mt-1 w-full" type="number" step="0.1" name="quebrado_max" :value="old('quebrado_max')" />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Opciones de Marketing (Opcional)</h3>
                        <div>
                            <x-input-label for="cantidad_acordada" value="Iniciar campaña con (sacos ya comprados)" />
                            <x-text-input id="cantidad_acordada" class="block mt-1 w-full" type="number" name="cantidad_acordada" :value="old('cantidad_acordada', 0)" />
                            <p class="mt-2 text-sm text-gray-500">Usa esto para crear urgencia. El valor real se sumará a este número.</p>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('campanas.index') }}" class="text-sm text-gray-600">Cancelar</a>
                            <x-primary-button class="ms-4">
                                {{ __('Publicar Campaña') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>