<x-app-layout>

    <style>
        :root { --color-primary: #5F7F3B; }
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 15px; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }
        .btn-primary { background-color: var(--color-primary); color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { background-color: #4b662e; }
        .btn-secondary { background-color: #f3f4f6; color: #374151; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-decoration: none; border: 1px solid #e5e7eb; }
        .btn-secondary:hover { background-color: #e5e7eb; }
        .section-title { font-size: 0.9rem; font-weight: 800; color: #5F7F3B; text-transform: uppercase; border-bottom: 2px solid #f3f4f6; padding-bottom: 5px; margin-bottom: 15px; margin-top: 20px; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-8">

                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-user-plus mr-3 text-green-600"></i> Registrar Nuevo Operario
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Crea un usuario para acceder al módulo de Casetas.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.usuarios-caseta.store') }}">
                        @csrf

                        <div class="section-title">Datos Personales</div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">DNI *</label>
                                <input type="text" name="dni" class="form-input" maxlength="8" value="{{ old('dni') }}" required>
                                @error('dni') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Primer Nombre *</label>
                                <input type="text" name="primer_nombre" class="form-input" value="{{ old('primer_nombre') }}" required>
                                @error('primer_nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Segundo Nombre</label>
                                <input type="text" name="segundo_nombre" class="form-input" value="{{ old('segundo_nombre') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Apellido Paterno *</label>
                                <input type="text" name="apellido_paterno" class="form-input" value="{{ old('apellido_paterno') }}" required>
                                @error('apellido_paterno') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Apellido Materno</label>
                                <input type="text" name="apellido_materno" class="form-input" value="{{ old('apellido_materno') }}">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Teléfono / Celular</label>
                            <input type="text" name="telefono" class="form-input" value="{{ old('telefono') }}">
                        </div>

                        <div class="section-title">Datos de Acceso</div>

                        <div class="mb-4">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Correo Electrónico *</label>
                            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
                            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Contraseña *</label>
                                <input type="password" name="password" class="form-input" required autocomplete="new-password">
                                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-2">Confirmar Contraseña *</label>
                                <input type="password" name="password_confirmation" class="form-input" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.usuarios-caseta.index') }}" class="btn-secondary">Cancelar</a>
                            <button type="submit" class="btn-primary">Registrar Operario</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>