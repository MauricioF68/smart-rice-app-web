<x-app-layout>
    
    <style>
        /* Estilos de Inputs para que sean Verdes al enfocar */
        .form-input, .form-select, .form-textarea {
            width: 100%;
            border: 1px solid #d1d5db; /* Gris suave */
            border-radius: 8px;
            padding: 10px 15px;
            outline: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            background-color: #fff;
        }

        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: var(--color-primary); /* Verde Corporativo */
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); /* Anillo suave verde */
        }

        /* Input Group para el Precio (S/) */
        .input-group {
            position: relative;
        }
        .input-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            font-weight: bold;
        }
        .input-with-prefix {
            padding-left: 35px; /* Espacio para el S/ */
        }

        /* Estilos de Botones Locales */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-confirm {
            background-color: var(--color-primary);
            color: white;
        }
        .btn-confirm:hover { background-color: #4b662e; transform: translateY(-1px); }

        .btn-cancel {
            background-color: white;
            color: #666;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Crear Nueva Preventa</h2>
                        <p class="text-sm text-gray-500">Define el lote y el precio para recibir ofertas.</p>
                    </div>
                    <a href="{{ route('preventas.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    
                    {{-- FORMULARIO --}}
                    <form method="POST" action="{{ route('preventas.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-2">
                                <label for="lote_id" class="block font-bold text-sm text-gray-700 mb-2">
                                    Selecciona el Lote a Vender <span class="text-red-500">*</span>
                                </label>
                                
                                <select name="lote_id" id="lote_id" class="form-select" required>
                                    <option value="">-- Elige un lote disponible --</option>
                                    @forelse ($lotesDisponibles as $lote)
                                        <option value="{{ $lote->id }}" @selected(old('lote_id') == $lote->id)>
                                            {{ $lote->nombre_lote }} — ({{ $lote->cantidad_disponible_sacos }} sacos | H: {{ $lote->humedad }}% | Q: {{ $lote->quebrado }}%)
                                        </option>
                                    @empty
                                        <option value="" disabled>No tienes lotes registrados o disponibles.</option>
                                    @endforelse
                                </select>
                                
                                @if($lotesDisponibles->isEmpty())
                                    <p class="text-sm text-red-500 mt-2">
                                        <i class="fas fa-exclamation-circle"></i> Primero debes registrar un lote en "Mis Lotes".
                                    </p>
                                @endif
                                <x-input-error :messages="$errors->get('lote_id')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1">
                                <label for="precio_por_saco" class="block font-bold text-sm text-gray-700 mb-2">
                                    Precio Sugerido por Saco <span class="text-red-500">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-prefix">S/</span>
                                    <input id="precio_por_saco" class="form-input input-with-prefix" type="number" name="precio_por_saco" step="0.01" placeholder="0.00" :value="old('precio_por_saco')" required />
                                </div>
                                <x-input-error :messages="$errors->get('precio_por_saco')" class="mt-2" />
                            </div>

                            <div class="col-span-2 md:col-span-1 flex items-center p-4 bg-yellow-50 rounded-lg border border-yellow-100 text-sm text-yellow-800">
                                <i class="fas fa-lightbulb text-xl mr-3 text-yellow-500"></i>
                                <p>Este precio es referencial. Los molinos podrán enviarte contrapropuestas que podrás aceptar o rechazar.</p>
                            </div>

                            <div class="col-span-2">
                                <label for="notas" class="block font-bold text-sm text-gray-700 mb-2">
                                    Notas Adicionales (Opcional)
                                </label>
                                <textarea id="notas" name="notas" rows="3" class="form-textarea" placeholder="Ej: Arroz cáscara de variedad NIR, cosecha reciente...">{{ old('notas') }}</textarea>
                                <x-input-error :messages="$errors->get('notas')" class="mt-2" />
                            </div>

                        </div>
                        
                        <div class="mt-8 flex items-center justify-end border-t border-gray-100 pt-6">
                            <a href="{{ route('preventas.index') }}" class="btn-action btn-cancel">
                                Cancelar
                            </a>
                            
                            <button type="submit" class="btn-action btn-confirm">
                                <i class="fas fa-paper-plane mr-2"></i> Publicar Preventa
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>