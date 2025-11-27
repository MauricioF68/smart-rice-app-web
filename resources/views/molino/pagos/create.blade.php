<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                
                {{-- ENCABEZADO VERDE --}}
                <div class="bg-[#5F7F3B] px-6 py-4 flex justify-between items-center text-white">
                    <h2 class="text-xl font-bold flex items-center">
                        <i class="fas fa-hand-holding-usd mr-3"></i> Registrar Pago a Agricultor
                    </h2>
                    <span class="bg-white/20 px-3 py-1 rounded text-sm font-semibold">
                        Operación #{{ $preventa->id }}
                    </span>
                </div>

                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- COLUMNA 1: DETALLES DE LIQUIDACIÓN (SOLO LECTURA) --}}
                    <div>
                        <h3 class="text-gray-800 font-bold text-lg mb-4 border-b pb-2">
                            1. Detalle de Liquidación
                        </h3>
                        
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 space-y-4">
                            
                            {{-- Datos del Agricultor --}}
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase">Beneficiario</p>
                                <p class="text-lg font-bold text-gray-800">{{ $preventa->user->name }}</p>
                                <p class="text-sm text-gray-600">DNI: {{ $preventa->user->dni }}</p>
                            </div>

                            <hr class="border-gray-200">

                            {{-- Desglose del Cálculo --}}
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Precio Pactado (x saco)</span>
                                <span class="font-mono font-bold">S/ {{ number_format($preventa->precio_por_saco, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Cantidad Real (Sacos)</span>
                                <span class="font-mono font-bold">x {{ $preventa->analisisCalidad->cantidad_sacos_real }}</span>
                            </div>
                            
                            <hr class="border-gray-300 border-dashed">

                            {{-- Total Final --}}
                            <div class="flex justify-between items-center text-xl text-[#5F7F3B]">
                                <span class="font-bold">Total a Pagar</span>
                                @php
                                    $total = $preventa->precio_por_saco * $preventa->analisisCalidad->cantidad_sacos_real;
                                @endphp
                                <span class="font-extrabold">S/ {{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 text-blue-800 rounded-lg text-sm flex items-start border border-blue-100">
                            <i class="fas fa-info-circle mt-1 mr-2"></i>
                            <p>Realiza la transferencia desde tu banco y adjunta el comprobante (voucher) en el formulario de la derecha para validar el pago.</p>
                        </div>
                    </div>

                    {{-- COLUMNA 2: FORMULARIO DE REGISTRO --}}
                    <div>
                        <h3 class="text-gray-800 font-bold text-lg mb-4 border-b pb-2">
                            2. Datos de la Transferencia
                        </h3>

                        <form method="POST" action="{{ route('molino.pagos.store', $preventa->id) }}" enctype="multipart/form-data">
                            @csrf

                            {{-- Banco --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Banco de Origen</label>
                                <select name="banco_origen" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                    <option value="">Selecciona un banco...</option>
                                    <option value="BCP">BCP</option>
                                    <option value="BBVA">BBVA</option>
                                    <option value="Interbank">Interbank</option>
                                    <option value="Scotiabank">Scotiabank</option>
                                    <option value="Banco de la Nación">Banco de la Nación</option>
                                    <option value="Otro">Otro / Efectivo</option>
                                </select>
                                @error('banco_origen') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Número de Operación --}}
                            <div class="mb-4">
                                <label class="block text-sm font-bold text-gray-700 mb-2">N° Operación / Código Voucher</label>
                                <input type="text" name="numero_operacion" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" placeholder="Ej: 12345678" required>
                                @error('numero_operacion') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Fecha del Pago (IMPORTANTE) --}}
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Fecha y Hora de Transferencia</label>
                                <input type="datetime-local" 
                                       name="fecha_pago" 
                                       class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" 
                                       required 
                                       value="{{ now()->format('Y-m-d\TH:i') }}">
                                <p class="text-xs text-gray-400 mt-1">Ajusta la fecha si hiciste el pago antes.</p>
                                @error('fecha_pago') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Foto Voucher --}}
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Foto del Voucher *</label>
                                <input type="file" 
                                       name="foto_voucher" 
                                       accept="image/*,.pdf" 
                                       class="block w-full text-sm text-gray-500 
                                              file:mr-4 file:py-2 file:px-4 
                                              file:rounded-full file:border-0 
                                              file:text-sm file:font-semibold 
                                              file:bg-green-50 file:text-green-700 
                                              hover:file:bg-green-100 cursor-pointer" 
                                       required>
                                <p class="text-xs text-gray-400 mt-1">Sube una imagen clara o PDF del comprobante.</p>
                                @error('foto_voucher') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>

                            {{-- Botones --}}
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('molino.pagos.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-300 transition">
                                    Cancelar
                                </a>
                                <button type="submit" onclick="return confirm('¿Estás seguro de confirmar este pago? La carga se marcará como PAGADA.');" class="px-6 py-2 bg-[#5F7F3B] text-white rounded-lg font-bold hover:bg-[#4b662e] transition shadow-lg flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i> Confirmar Pago
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>