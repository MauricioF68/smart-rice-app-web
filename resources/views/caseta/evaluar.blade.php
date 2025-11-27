<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluación de Calidad') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                
                {{-- ENCABEZADO --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Ficha de Ingreso #{{ $preventa->id }}</h3>
                        <p class="text-sm text-gray-500">Agricultor: {{ $preventa->user->name }} (DNI: {{ $preventa->user->dni }})</p>
                    </div>
                    <div class="text-right">
                         <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wide">
                            En Evaluación
                        </span>
                    </div>
                </div>

                {{-- FORMULARIO --}}
                {{-- IMPORTANTE: enctype="multipart/form-data" es obligatorio para subir archivos --}}
                <form method="POST" action="{{ route('caseta.guardar-analisis', $preventa->id) }}" class="p-6" enctype="multipart/form-data">
                    @csrf

                    {{-- GRID COMPARATIVO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        
                        {{-- COLUMNA IZQUIERDA: DATOS ACORDADOS (Solo lectura) --}}
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 opacity-80">
                            <h4 class="font-bold text-gray-500 uppercase text-xs mb-4 border-b border-gray-200 pb-2">
                                <i class="fas fa-handshake mr-1"></i> Datos Declarados (Acuerdo)
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400">Cantidad Declarada</label>
                                    <p class="text-lg font-bold text-gray-700">{{ $preventa->cantidad_sacos }} sacos</p>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400">Humedad</label>
                                        <p class="font-mono text-gray-600">{{ $preventa->humedad }}%</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-400">Quebrado</label>
                                        <p class="font-mono text-gray-600">{{ $preventa->quebrado }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- COLUMNA DERECHA: DATOS REALES (Formulario) --}}
                        <div class="bg-green-50 p-5 rounded-lg border border-green-200 shadow-inner">
                            <h4 class="font-bold text-green-700 uppercase text-xs mb-4 border-b border-green-200 pb-2">
                                <i class="fas fa-balance-scale mr-1"></i> Datos Reales (Medición en Caseta)
                            </h4>

                            <div class="space-y-4">
                                {{-- Peso y Sacos --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Cant. Sacos Real *</label>
                                        <input type="number" name="cantidad_sacos_real" value="{{ old('cantidad_sacos_real', $preventa->cantidad_sacos) }}" class="w-full rounded border-green-300 focus:border-green-500 focus:ring-green-500 font-bold text-gray-800" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-600 mb-1">Peso Total (Kg) *</label>
                                        <input type="number" step="0.01" name="peso_real_sacos" class="w-full rounded border-green-300 focus:border-green-500 focus:ring-green-500 font-bold text-gray-800" placeholder="0.00" required autofocus>
                                    </div>
                                </div>

                                {{-- Calidad --}}
                                <div class="grid grid-cols-3 gap-2">
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Humedad (%) *</label>
                                        <input type="number" step="0.01" name="humedad_real" class="w-full rounded border-green-300 focus:border-green-500 focus:ring-green-500 text-sm" placeholder="%" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Impurezas (%) *</label>
                                        <input type="number" step="0.01" name="impurezas_real" class="w-full rounded border-green-300 focus:border-green-500 focus:ring-green-500 text-sm" placeholder="%" required>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-gray-600 mb-1">Quebrado (%) *</label>
                                        <input type="number" step="0.01" name="quebrado_real" class="w-full rounded border-green-300 focus:border-green-500 focus:ring-green-500 text-sm" placeholder="%" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN DE FOTO TICKET (NUEVA) --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-camera mr-1 text-gray-500"></i> Foto del Ticket de Balanza (Opcional)
                        </label>
                        <input type="file" 
                               name="foto_ticket_balanza" 
                               accept="image/*"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-full file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-green-50 file:text-green-700
                                      hover:file:bg-green-100 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Sube una foto clara del peso impreso para validación.</p>
                        @error('foto_ticket_balanza') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Observaciones del Operario</label>
                        <textarea name="observaciones" rows="2" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Ej: Sacos llegaron mojados, carga aprobada sin novedad..."></textarea>
                    </div>

                    {{-- BOTONES --}}
                    <div class="flex justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('caseta.recepcion') }}" class="px-6 py-3 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition">
                            Cancelar
                        </a>
                        <button type="submit" onclick="return confirm('¿Confirmar que los datos y la foto son correctos? Esta acción cerrará la recepción.');" class="px-6 py-3 bg-[#5F7F3B] text-white font-bold rounded-lg hover:bg-[#4b662e] shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-save mr-2"></i> Guardar y Finalizar Recepción
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>