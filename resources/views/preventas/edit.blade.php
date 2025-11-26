<x-app-layout>
    
    <style>
        /* Estilos de Inputs */
        .form-input, .form-textarea {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 10px 15px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background-color: #fff;
        }

        .form-input:focus, .form-textarea:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1);
        }

        /* Inputs Deshabilitados (Solo lectura) */
        .input-disabled {
            background-color: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
            border-color: #e5e7eb;
        }

        /* Input Group para el Precio (S/) */
        .input-group { position: relative; }
        .input-prefix {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #6b7280; font-weight: bold;
        }
        .input-with-prefix { padding-left: 35px; }

        /* Botones */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 24px; border-radius: 8px; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }

        .btn-update {
            background-color: var(--color-secondary); /* Amarillo para edición */
            color: white;
        }
        .btn-update:hover { background-color: #9a8235; transform: translateY(-1px); }

        .btn-cancel {
            background-color: white; color: #666; border: 1px solid #ccc; margin-right: 10px;
        }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-yellow-800">
                            <i class="fas fa-edit mr-2"></i> Editar Preventa #PV-{{ $preventa->id }}
                        </h2>
                        <p class="text-sm text-yellow-600 mt-1">Actualiza el precio o las notas de tu oferta.</p>
                    </div>
                    <a href="{{ route('preventas.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    
                    <form method="POST" action="{{ route('preventas.update', $preventa) }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-2 p-4 bg-gray-50 rounded-lg border border-gray-200 mb-2">
                                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">
                                    Información del Lote (No editable)
                                </h3>
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="text-xs text-gray-400">Cantidad Sacos</label>
                                        <p class="font-bold text-gray-800 text-lg">{{ $preventa->cantidad_sacos }}</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400">Humedad</label>
                                        <p class="font-bold text-gray-800 text-lg">{{ $preventa->humedad }}%</p>
                                    </div>
                                    <div>
                                        <label class="text-xs text-gray-400">Quebrado</label>
                                        <p class="font-bold text-gray-800 text-lg">{{ $preventa->quebrado }}%</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label for="precio_por_saco" class="block font-bold text-sm text-gray-700 mb-2">
                                    Precio Sugerido por Saco <span class="text-red-500">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-prefix">S/</span>
                                    <input id="precio_por_saco" class="form-input input-with-prefix" type="number" name="precio_por_saco" step="0.01" :value="old('precio_por_saco', {{ $preventa->precio_por_saco }})" required />
                                </div>
                                <x-input-error :messages="$errors->get('precio_por_saco')" class="mt-2" />
                            </div>

                            <div class="col-span-2">
                                <label for="notas" class="block font-bold text-sm text-gray-700 mb-2">
                                    Notas Adicionales
                                </label>
                                <textarea id="notas" name="notas" rows="3" class="form-textarea">{{ old('notas', $preventa->notas) }}</textarea>
                                <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                            </div>

                        </div>
                        
                        <div class="mt-8 flex items-center justify-end border-t border-gray-100 pt-6">
                            <a href="{{ route('preventas.index') }}" class="btn-action btn-cancel">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="btn-action btn-update">
                                <i class="fas fa-save mr-2"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>