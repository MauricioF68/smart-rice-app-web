<x-app-layout>

    <style>
        :root { --color-primary: #5F7F3B; }
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 15px; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }
        .btn-primary { background-color: var(--color-primary); color: white; padding: 10px 20px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; }
        .btn-primary:hover { background-color: #4b662e; }
        .btn-secondary { background-color: #f3f4f6; color: #374151; padding: 10px 20px; border-radius: 8px; font-weight: 700; text-decoration: none; border: 1px solid #e5e7eb; }
        .btn-secondary:hover { background-color: #e5e7eb; }
    </style>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-8">

                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-edit mr-3 text-green-600"></i> Editar Caseta
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Modifica los datos de la caseta {{ $caseta->codigo_unico }}.</p>
                    </div>

                    <form method="POST" action="{{ route('admin.casetas.update', $caseta->id) }}">
                        @csrf
                        @method('PUT') {{-- Importante para actualizar --}}

                        {{-- Nombre --}}
                        <div class="mb-4">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Nombre de la Caseta</label>
                            <input type="text" name="nombre" class="form-input" 
                                   value="{{ old('nombre', $caseta->nombre) }}" required>
                            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Código --}}
                        <div class="mb-4">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Código Único</label>
                            <input type="text" name="codigo_unico" class="form-input uppercase" 
                                   value="{{ old('codigo_unico', $caseta->codigo_unico) }}" required>
                            @error('codigo_unico') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        {{-- Ubicación --}}
                        <div class="mb-6">
                            <label class="block font-bold text-sm text-gray-700 mb-2">Ubicación / Dirección</label>
                            <input type="text" name="ubicacion" class="form-input" 
                                   value="{{ old('ubicacion', $caseta->ubicacion) }}">
                            @error('ubicacion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                            <a href="{{ route('admin.casetas.index') }}" class="btn-secondary">Cancelar</a>
                            <button type="submit" class="btn-primary">Actualizar Caseta</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>