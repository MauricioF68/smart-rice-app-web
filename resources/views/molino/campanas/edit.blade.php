<x-app-layout>
    
    <!-- Estilos específicos -->
    <style>
        /* Inputs Editables */
        .form-input, .form-select {
            width: 100%; border: 1px solid #d1d5db; border-radius: 8px;
            padding: 10px 15px; outline: none; transition: all 0.3s;
            font-size: 0.95rem; background-color: #fff;
        }
        .form-input:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1);
        }

        /* Inputs Deshabilitados (Read-only) */
        .input-disabled {
            background-color: #f3f4f6; /* Gris suave */
            color: #6b7280;
            border-color: #e5e7eb;
            cursor: not-allowed;
        }

        /* Input Groups */
        .input-group { position: relative; }
        .input-suffix {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            color: #9ca3af; font-weight: bold; font-size: 0.9rem;
        }
        .input-prefix {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            color: #6b7280; font-weight: bold;
        }
        .pl-8 { padding-left: 35px; } 

        /* Botones */
        .btn-action {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 12px 24px; border-radius: 8px; font-weight: 700;
            border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
        }
        
        /* Botón Amarillo para Editar */
        .btn-update { background-color: var(--color-secondary); color: white; }
        .btn-update:hover { background-color: #9a8235; transform: translateY(-1px); }

        .btn-cancel { background-color: white; color: #666; border: 1px solid #ccc; margin-right: 10px; }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <!-- Encabezado Amarillo -->
                <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-yellow-800 flex items-center">
                            <i class="fas fa-edit mr-2"></i> Editar Campaña
                        </h2>
                        <p class="text-sm text-yellow-700">Modifica los parámetros de tu campaña activa.</p>
                    </div>
                    <a href="{{ route('campanas.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('campanas.update', $campana) }}">
                        @csrf
                        @method('PUT')

                        <!-- SECCIÓN 1: DATOS GENERALES -->
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-700 mb-4 flex items-center border-b pb-2">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i> Datos Generales
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <!-- Nombre (Bloqueado) -->
                                <div class="md:col-span-2">
                                    <label class="block font-bold text-sm text-gray-500 mb-2">Nombre de la Campaña <span class="text-xs font-normal">(No editable)</span></label>
                                    <input type="text" class="form-input input-disabled" value="{{ $campana->nombre_campana }}" disabled />
                                </div>

                                <!-- Variedad (Bloqueado) -->
                                <div>
                                    <label class="block font-bold text-sm text-gray-500 mb-2">Variedad de Arroz <span class="text-xs font-normal">(No editable)</span></label>
                                    <select class="form-select input-disabled" disabled>
                                        @foreach ($tiposArroz as $tipo)
                                            <option value="{{ $tipo->id }}" @selected($campana->tipo_arroz_id == $tipo->id)>
                                                {{ $tipo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Cantidad Total (Editable) -->
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Meta Total de Sacos <span class="text-green-600 text-xs"><i class="fas fa-pen"></i> Editable</span></label>
                                    <input type="number" name="cantidad_total" class="form-input" value="{{ old('cantidad_total', $campana->cantidad_total) }}" required />
                                    <x-input-error :messages="$errors->get('cantidad_total')" class="mt-2" />
                                </div>

                                <!-- Precio Base (Editable) -->
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Precio Base por Saco <span class="text-green-600 text-xs"><i class="fas fa-pen"></i> Editable</span></label>
                                    <div class="input-group">
                                        <span class="input-prefix">S/</span>
                                        <input type="number" name="precio_base" step="0.01" class="form-input pl-8" value="{{ old('precio_base', $campana->precio_base) }}" required />
                                    </div>
                                    <x-input-error :messages="$errors->get('precio_base')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: REGLAS POR AGRICULTOR (Editables) -->
                        <div class="mb-8 bg-yellow-50 p-5 rounded-xl border border-yellow-100">
                            <h3 class="text-md font-bold text-yellow-800 mb-4 flex items-center">
                                <i class="fas fa-user-friends mr-2"></i> Reglas por Agricultor <span class="text-yellow-600 text-xs font-normal ml-2">(Puedes ajustarlas)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Mínimo de Sacos</label>
                                    <input type="number" name="min_sacos_por_agricultor" class="form-input" value="{{ old('min_sacos_por_agricultor', $campana->min_sacos_por_agricultor) }}" />
                                    <x-input-error :messages="$errors->get('min_sacos_por_agricultor')" class="mt-2" />
                                </div>
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Máximo de Sacos</label>
                                    <input type="number" name="max_sacos_por_agricultor" class="form-input" value="{{ old('max_sacos_por_agricultor', $campana->max_sacos_por_agricultor) }}" />
                                    <x-input-error :messages="$errors->get('max_sacos_por_agricultor')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: CALIDAD (Bloqueada) -->
                        <div class="mb-8 opacity-75">
                            <h3 class="text-lg font-bold text-gray-500 mb-4 flex items-center border-b pb-2">
                                <i class="fas fa-lock mr-2 text-gray-400"></i> Requisitos de Calidad <span class="text-xs font-normal ml-2">(Definidos al crear)</span>
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block font-bold text-xs text-gray-400 mb-1">Humedad Mín.</label>
                                    <div class="input-group">
                                        <input type="number" class="form-input input-disabled" value="{{ $campana->humedad_min }}" disabled />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-400 mb-1">Humedad Máx.</label>
                                    <div class="input-group">
                                        <input type="number" class="form-input input-disabled" value="{{ $campana->humedad_max }}" disabled />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-400 mb-1">Quebrado Mín.</label>
                                    <div class="input-group">
                                        <input type="number" class="form-input input-disabled" value="{{ $campana->quebrado_min }}" disabled />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block font-bold text-xs text-gray-400 mb-1">Quebrado Máx.</label>
                                    <div class="input-group">
                                        <input type="number" class="form-input input-disabled" value="{{ $campana->quebrado_max }}" disabled />
                                        <span class="input-suffix">%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: MARKETING (Bloqueado) -->
                        <div class="mb-6">
                            <label class="block font-bold text-sm text-gray-500 mb-2">Sacos de arranque (Marketing)</label>
                            <input type="number" class="form-input input-disabled md:w-1/3" value="{{ $campana->cantidad_acordada }}" disabled />
                            <p class="mt-1 text-xs text-gray-400">Este valor no se puede reducir una vez iniciada la campaña.</p>
                        </div>

                        <!-- BOTONES -->
                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('campanas.index') }}" class="btn-action btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-action btn-update">
                                <i class="fas fa-sync-alt mr-2"></i> Actualizar Campaña
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>