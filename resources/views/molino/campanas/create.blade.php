<x-app-layout>
    
    <style>
        /* Inputs */
        .form-input, .form-select {
            width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
            padding: 10px 15px; outline: none; transition: all 0.3s;
            font-size: 0.95rem; background-color: #fff;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1);
        }

        /* Input Groups (Iconos dentro del input) */
        .input-group { position: relative; }
        .input-suffix {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-weight: bold; font-size: 0.9rem;
        }
        .input-prefix {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #6b7280; font-weight: bold;
        }
        .pl-8 { padding-left: 35px; } /* Espacio para prefijo */

        /* Botones */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 24px; border-radius: 8px; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        .btn-confirm { background-color: var(--color-primary); color: white; }
        .btn-confirm:hover { background-color: #4b662e; transform: translateY(-1px); }

        .btn-cancel { background-color: white; color: #666; border: 1px solid #ccc; margin-right: 10px; }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-plus-circle mr-2 text-green-600"></i> Crear Nueva Campaña
                        </h2>
                        <p class="text-sm text-gray-500">Publica tu necesidad de compra para recibir ofertas.</p>
                    </div>
                    <a href="{{ route('campanas.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('campanas.store') }}">
                        @csrf

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center border-b pb-2">
                                <i class="fas fa-info-circle mr-2 text-yellow-500"></i> Datos Generales
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div class="md:col-span-2">
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre de la Campaña <span class="text-red-500">*</span></label>
                                    <input type="text" name="nombre_campana" class="form-input" value="{{ old('nombre_campana') }}" required placeholder="Ej. Compra Arroz Cáscara - Noviembre 2024" />
                                    <x-input-error :messages="$errors->get('nombre_campana')" class="mt-2" />
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Variedad de Arroz <span class="text-red-500">*</span></label>
                                    <select name="tipo_arroz_id" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        @foreach ($tiposArroz as $tipo)
                                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Meta Total de Sacos <span class="text-red-500">*</span></label>
                                    <input type="number" name="cantidad_total" class="form-input" value="{{ old('cantidad_total') }}" required placeholder="Ej. 1000" />
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Precio Base por Saco <span class="text-red-500">*</span></label>
                                    <div class="input-group">
                                        <span class="input-prefix">S/</span>
                                        <input type="number" name="precio_base" step="0.01" class="form-input pl-8" value="{{ old('precio_base') }}" required placeholder="0.00" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 bg-gray-50 p-5 rounded-xl border border-gray-200">
                            <h3 class="text-md font-bold text-gray-700 mb-4 flex items-center">
                                <i class="fas fa-user-friends mr-2 text-blue-500"></i> Reglas por Agricultor <span class="text-gray-400 text-xs font-normal ml-2">(Opcional)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Mínimo de Sacos</label>
                                    <input type="number" name="min_sacos_por_agricultor" class="form-input" value="{{ old('min_sacos_por_agricultor') }}" placeholder="Ej. 50" />
                                </div>
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Máximo de Sacos</label>
                                    <input type="number" name="max_sacos_por_agricultor" class="form-input" value="{{ old('max_sacos_por_agricultor') }}" placeholder="Ej. 500" />
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center border-b pb-2">
                                <i class="fas fa-flask mr-2 text-purple-500"></i> Requisitos de Calidad <span class="text-gray-400 text-xs font-normal ml-2">(Opcional)</span>
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block font-bold text-xs text-gray-600 mb-1">Humedad Mín.</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" name="humedad_min" class="form-input" value="{{ old('humedad_min') }}" />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-600 mb-1">Humedad Máx.</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" name="humedad_max" class="form-input" value="{{ old('humedad_max') }}" />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-600 mb-1">Quebrado Mín.</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" name="quebrado_min" class="form-input" value="{{ old('quebrado_min') }}" />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-600 mb-1">Quebrado Máx.</label>
                                    <div class="input-group">
                                        <input type="number" step="0.1" name="quebrado_max" class="form-input" value="{{ old('quebrado_max') }}" />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Iniciar campaña con (sacos ya comprados)</label>
                            <input type="number" name="cantidad_acordada" class="form-input md:w-1/3" value="{{ old('cantidad_acordada', 0) }}" />
                            <p class="mt-1 text-xs text-gray-500"><i class="fas fa-lightbulb text-yellow-500"></i> Usa esto para crear sensación de urgencia.</p>
                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('campanas.index') }}" class="btn-action btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-action btn-confirm">
                                <i class="fas fa-paper-plane mr-2"></i> Publicar Campaña
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>