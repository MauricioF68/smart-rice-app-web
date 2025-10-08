<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Nueva Preventa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6">Selecciona uno de tus lotes disponibles y define el precio para publicarlo en el mercado.</p>

                    <form method="POST" action="{{ route('preventas.store') }}">
                        @csrf

                        <div class="grid grid-cols-2 gap-6">

                            <div class="col-span-2">
                                <x-input-label for="lote_id" :value="__('Selecciona el Lote a Vender')" />
                                <select name="lote_id" id="lote_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Elige un lote --</option>
                                    @forelse ($lotesDisponibles as $lote)
                                        <option value="{{ $lote->id }}" @selected(old('lote_id') == $lote->id)>
                                            {{ $lote->nombre_lote }} ({{ $lote->cantidad_disponible_sacos }} sacos disponibles | Humedad: {{ $lote->humedad }}% | Quebrado: {{ $lote->quebrado }}%)
                                        </option>
                                    @empty
                                        <option value="" disabled>No tienes lotes disponibles para la venta.</option>
                                    @endforelse
                                </select>
                                <x-input-error :messages="$errors->get('lote_id')" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <x-input-label for="precio_por_saco" :value="__('Precio Sugerido por Saco (S/)')" />
                                <x-text-input id="precio_por_saco" class="block mt-1 w-full" type="number" name="precio_por_saco" step="0.01" :value="old('precio_por_saco')" required />
                                <x-input-error :messages="$errors->get('precio_por_saco')" class="mt-2" />
                            </div>

                        </div>
                        
                        <div class="mt-6">
                            <x-input-label for="notas" :value="__('Notas Adicionales (Opcional)')" />
                            <textarea id="notas" name="notas" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notas') }}</textarea>
                            <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('preventas.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                            <x-primary-button class="ms-4">
                                {{ __('Publicar Preventa') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>