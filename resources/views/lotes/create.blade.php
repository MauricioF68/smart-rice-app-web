<x-app-layout>
    
    <style>
        /* Estilos de Inputs */
        .form-input, .form-select {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 15px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background-color: #fff;
        }

        .form-input:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1);
        }

        /* Botones */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 24px; border-radius: 8px; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }

        .btn-confirm {
            background-color: var(--color-primary);
            color: white;
        }
        .btn-confirm:hover { background-color: #4b662e; transform: translateY(-1px); }

        .btn-cancel {
            background-color: white; color: #666; border: 1px solid #ccc; margin-right: 10px;
        }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-tractor mr-2 text-green-600"></i> Registrar Nuevo Lote
                        </h2>
                        <p class="text-sm text-gray-500">Ingresa los datos de tu cosecha para añadirla al inventario.</p>
                    </div>
                    <a href="{{ route('lotes.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    
                    <form method="POST" action="{{ route('lotes.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            
                            <div class="col-span-1 md:col-span-2">
                                <label for="nombre_lote" class="block font-bold text-sm text-gray-700 mb-2">
                                    Nombre o Alias del Lote <span class="text-red-500">*</span>
                                </label>
                                <input id="nombre_lote" type="text" name="nombre_lote" class="form-input" value="{{ old('nombre_lote') }}" required placeholder="Ej. Cosecha Sector Norte - Noviembre" />
                                <x-input-error :messages="$errors->get('nombre_lote')" class="mt-2" />
                            </div>

                            <div>
                                <label for="tipo_arroz_id" class="block font-bold text-sm text-gray-700 mb-2">
                                    Variedad de Arroz <span class="text-red-500">*</span>
                                </label>
                                <select id="tipo_arroz_id" name="tipo_arroz_id" class="form-select" required>
                                    <option value="">-- Selecciona una variedad --</option>
                                    @foreach ($tiposArroz as $tipo)
                                        <option value="{{ $tipo->id }}" @selected(old('tipo_arroz_id') == $tipo->id)>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_arroz_id')" class="mt-2" />
                            </div>

                            <div>
                                <label for="cantidad_total_sacos" class="block font-bold text-sm text-gray-700 mb-2">
                                    Cantidad Total de Sacos <span class="text-red-500">*</span>
                                </label>
                                <input id="cantidad_total_sacos" type="number" name="cantidad_total_sacos" class="form-input" value="{{ old('cantidad_total_sacos') }}" required placeholder="0" />
                                <x-input-error :messages="$errors->get('cantidad_total_sacos')" class="mt-2" />
                            </div>

                        </div>

                        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 border-b pb-2 flex items-center">
                                <i class="fas fa-flask mr-2 text-gray-400"></i> Análisis de Calidad (Estimado)
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="humedad" class="block font-bold text-sm text-gray-700 mb-2">
                                        Humedad (%) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="humedad" type="number" name="humedad" step="0.1" class="form-input" value="{{ old('humedad') }}" required placeholder="Ej. 14.5" />
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold">%</span>
                                    </div>
                                    <x-input-error :messages="$errors->get('humedad')" class="mt-2" />
                                </div>

                                <div>
                                    <label for="quebrado" class="block font-bold text-sm text-gray-700 mb-2">
                                        Quebrado (%) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="quebrado" type="number" name="quebrado" step="0.1" class="form-input" value="{{ old('quebrado') }}" required placeholder="Ej. 5.0" />
                                        <span class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 font-bold">%</span>
                                    </div>
                                    <x-input-error :messages="$errors->get('quebrado')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('lotes.index') }}" class="btn-action btn-cancel">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="btn-action btn-confirm">
                                <i class="fas fa-save mr-2"></i> Registrar Lote
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>