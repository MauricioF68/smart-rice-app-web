<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Registrar Nuevo Lote') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('lotes.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="nombre_lote" value="Nombre o Alias del Lote" />
                                <x-text-input id="nombre_lote" class="block mt-1 w-full" type="text" name="nombre_lote" :value="old('nombre_lote')" required />
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
                                <x-input-label for="cantidad_total_sacos" value="Cantidad Total de Sacos" />
                                <x-text-input id="cantidad_total_sacos" class="block mt-1 w-full" type="number" name="cantidad_total_sacos" :value="old('cantidad_total_sacos')" required />
                            </div>
                        </div>

                        <h3 class="text-lg font-bold mt-6 mb-4 border-b pb-2">Análisis de Calidad (Estimado)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="humedad" value="Humedad (%)" />
                                <x-text-input id="humedad" class="block mt-1 w-full" type="number" name="humedad" step="0.1" :value="old('humedad')" required />
                            </div>
                            <div>
                                <x-input-label for="quebrado" value="Quebrado (%)" />
                                <x-text-input id="quebrado" class="block mt-1 w-full" type="number" name="quebrado" step="0.1" :value="old('quebrado')" required />
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('lotes.index') }}" class="text-sm text-gray-600">Cancelar</a>
                            <x-primary-button class="ms-4">
                                Registrar Lote
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>