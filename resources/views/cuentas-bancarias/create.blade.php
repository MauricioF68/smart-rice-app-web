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
                            <i class="fas fa-university mr-2 text-green-600"></i> Registrar Cuenta Bancaria
                        </h2>
                        <p class="text-sm text-gray-500">Ingresa los datos para recibir tus pagos.</p>
                    </div>
                    <a href="{{ route('cuentas-bancarias.index') }}" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </a>
                </div>

                <div class="p-8">
                    
                    <form method="POST" action="{{ route('cuentas-bancarias.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            
                            <div>
                                <label for="banco_nombre" class="block font-bold text-sm text-gray-700 mb-2">
                                    Banco <span class="text-red-500">*</span>
                                </label>
                                <select name="banco_nombre" id="banco_nombre" class="form-select" required>
                                    <option value="">-- Selecciona un banco --</option>
                                    @foreach ($bancos as $banco)
                                        <option value="{{ $banco }}" @selected(old('banco_nombre') == $banco)>{{ $banco }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('banco_nombre')" class="mt-2" />
                            </div>

                            <div>
                                <label for="tipo_cuenta" class="block font-bold text-sm text-gray-700 mb-2">
                                    Tipo de Cuenta <span class="text-red-500">*</span>
                                </label>
                                <select name="tipo_cuenta" id="tipo_cuenta" class="form-select" required>
                                    <option value="">-- Selecciona un tipo --</option>
                                    @foreach ($tipos_cuenta as $tipo)
                                        <option value="{{ $tipo }}" @selected(old('tipo_cuenta') == $tipo)>{{ $tipo }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('tipo_cuenta')" class="mt-2" />
                            </div>

                            <div class="col-span-1 md:col-span-2 border-t border-gray-100 my-2"></div>

                            <div>
                                <label for="titular_nombres" class="block font-bold text-sm text-gray-700 mb-2">
                                    Nombres del Titular <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="titular_nombres" id="titular_nombres" class="form-input" value="{{ old('titular_nombres') }}" required placeholder="Ej. Juan Carlos">
                                <x-input-error :messages="$errors->get('titular_nombres')" class="mt-2" />
                            </div>

                            <div>
                                <label for="titular_apellidos" class="block font-bold text-sm text-gray-700 mb-2">
                                    Apellidos del Titular <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="titular_apellidos" id="titular_apellidos" class="form-input" value="{{ old('titular_apellidos') }}" required placeholder="Ej. Pérez Gómez">
                                <x-input-error :messages="$errors->get('titular_apellidos')" class="mt-2" />
                            </div>

                            <div>
                                <label for="numero_cuenta" class="block font-bold text-sm text-gray-700 mb-2">
                                    Número de Cuenta <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="numero_cuenta" id="numero_cuenta" class="form-input" value="{{ old('numero_cuenta') }}" required placeholder="000-00000000-0-00">
                                <x-input-error :messages="$errors->get('numero_cuenta')" class="mt-2" />
                            </div>

                            <div>
                                <label for="cci" class="block font-bold text-sm text-gray-700 mb-2">
                                    CCI (Interbancaria) <span class="text-gray-400 font-normal">(Opcional)</span>
                                </label>
                                <input type="text" name="cci" id="cci" class="form-input" value="{{ old('cci') }}" placeholder="002-000-000000000000-00">
                                <x-input-error :messages="$errors->get('cci')" class="mt-2" />
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('cuentas-bancarias.index') }}" class="btn-action btn-cancel">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-action btn-confirm">
                                <i class="fas fa-save mr-2"></i> Guardar Cuenta
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>