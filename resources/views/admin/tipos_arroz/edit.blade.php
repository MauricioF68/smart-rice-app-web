<x-app-layout>

    {{-- 1. ESTILOS (Mismos que en el Index para consistencia) --}}
    <style>
        :root { --color-primary: #5F7F3B; }

        /* Input personalizado */
        .form-input { 
            width: 100%; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            padding: 10px 15px; 
            outline: none; 
            transition: all 0.3s; 
        }
        .form-input:focus { 
            border-color: var(--color-primary); 
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); 
        }

        /* Botón Guardar (Verde SmartRice) */
        .btn-primary { 
            background-color: var(--color-primary); 
            color: white; 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 0.9rem; 
            border: none; 
            cursor: pointer; 
            transition: all 0.2s; 
            display: inline-flex; 
            align-items: center; 
            justify-content: center;
        }
        .btn-primary:hover { background-color: #4b662e; transform: translateY(-1px); }

        /* Botón Cancelar (Gris) */
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            padding: 10px 20px; 
            border-radius: 8px; 
            font-weight: 700; 
            font-size: 0.9rem; 
            text-decoration: none;
            transition: all 0.2s;
            border: 1px solid #e5e7eb;
            display: inline-flex; 
            align-items: center; 
        }
        .btn-secondary:hover { background-color: #e5e7eb; }
    </style>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            {{-- TARJETA PRINCIPAL --}}
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    
                    {{-- TÍTULO CON ICONO --}}
                    <div class="mb-6 border-b border-gray-100 pb-4">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-pen-to-square mr-3 text-green-600"></i> Editar Tipo de Arroz
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Modifica el nombre de la variedad seleccionada.</p>
                    </div>

                    {{-- FORMULARIO --}}
                    <form method="POST" action="{{ route('admin.tipos-arroz.update', $tiposArroz) }}">
                        @csrf
                        @method('PUT')

                        {{-- CAMPO NOMBRE --}}
                        <div class="mb-6">
                            <label for="nombre" class="block font-bold text-sm text-gray-700 mb-2 uppercase tracking-wide">
                                Nombre de la Variedad
                            </label>
                            
                            {{-- Usamos el input HTML normal con tu clase .form-input --}}
                            <input 
                                id="nombre" 
                                class="form-input" 
                                type="text" 
                                name="nombre" 
                                value="{{ old('nombre', $tiposArroz->nombre) }}" 
                                required 
                                autofocus 
                                placeholder="Ej: Arroz Cáscara"
                            />

                            {{-- MENSAJE DE ERROR --}}
                            @error('nombre')
                                <span class="text-red-500 text-sm mt-2 block font-medium">
                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- BOTONES DE ACCIÓN --}}
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                             
                             {{-- Cancelar --}}
                             <a href="{{ route('admin.tipos-arroz.index') }}" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i> Cancelar
                             </a>

                             {{-- Guardar --}}
                             <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i> Actualizar
                             </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>