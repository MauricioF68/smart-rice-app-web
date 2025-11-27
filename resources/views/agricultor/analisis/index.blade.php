<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Certificados de Calidad') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @forelse ($analisis as $item)
                {{-- TARJETA DE CERTIFICADO --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-8 border-t-4 border-[#B79C43] relative">
                    
                    {{-- Marca de Agua / Sello (Visual) --}}
                    <div class="absolute top-0 right-0 p-4 opacity-10">
                        <i class="fas fa-certificate text-8xl text-[#B79C43]"></i>
                    </div>

                    <div class="p-6 relative z-10">
                        
                        {{-- Cabecera del Certificado --}}
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b border-dashed border-gray-300 pb-4">
                            <div>
                                <h3 class="text-xl font-bold text-[#5F7F3B]">
                                    <i class="fas fa-check-circle mr-2"></i> Certificado de Calidad #{{ str_pad($item->id, 6, '0', STR_PAD_LEFT) }}
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Emitido por: <strong>{{ $item->caseta->nombre }}</strong> | Fecha: {{ $item->created_at->format('d/m/Y h:i A') }}
                                </p>
                            </div>
                            <div class="mt-2 md:mt-0">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full uppercase border border-green-200">
                                    Verificado por SmartRice
                                </span>
                            </div>
                        </div>

                        {{-- Cuerpo: Comparativa --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- LADO A: Lo que prometiste --}}
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Declarado en Negociación</h4>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600 text-sm">Sacos:</span>
                                    <span class="font-bold text-gray-800">{{ $item->preventa->cantidad_sacos }}</span>
                                </div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600 text-sm">Humedad Base:</span>
                                    <span class="font-bold text-gray-800">{{ $item->preventa->humedad }}%</span>
                                </div>
                            </div>

                            {{-- LADO B: La Realidad (Destacado) --}}
                            <div class="bg-[#fcfdec] p-4 rounded-lg border border-[#B79C43] relative overflow-hidden">
                                <div class="absolute top-0 left-0 w-1 h-full bg-[#B79C43]"></div>
                                <h4 class="text-xs font-bold text-[#B79C43] uppercase mb-3 ml-2">Resultado Final (Caseta)</h4>
                                
                                <div class="grid grid-cols-2 gap-4 ml-2">
                                    <div>
                                        <p class="text-xs text-gray-500">Peso Neto</p>
                                        <p class="text-lg font-bold text-gray-900">{{ number_format($item->peso_real_sacos, 2) }} kg</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Sacos Reales</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $item->cantidad_sacos_real }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Humedad</p>
                                        <p class="text-lg font-bold {{ $item->humedad_real > $item->preventa->humedad ? 'text-red-600' : 'text-green-600' }}">
                                            {{ $item->humedad_real }}%
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Quebrado</p>
                                        <p class="text-lg font-bold text-gray-900">{{ $item->quebrado_real }}%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer: Observaciones y Ticket --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex-1">
                                <p class="text-xs font-bold text-gray-400 uppercase">Observaciones de Caseta:</p>
                                <p class="text-sm text-gray-600 italic">"{{ $item->observaciones ?? 'Sin observaciones.' }}"</p>
                            </div>
                            
                            @if($item->foto_ticket_balanza)
                                <button onclick="openImageModal('{{ asset('storage/' . $item->foto_ticket_balanza) }}')" 
                                        class="flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition text-sm font-bold shadow-md">
                                    <i class="fas fa-receipt mr-2"></i> Ver Ticket de Balanza
                                </button>
                            @else
                                <span class="text-xs text-gray-400 italic">Sin foto de ticket</span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Aún no tienes certificados</h3>
                    <p class="text-gray-500 mt-2">Tus análisis aparecerán aquí cuando la caseta procese tu carga.</p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- MODAL PARA VER FOTO --}}
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-80 hidden items-center justify-center z-50 transition-opacity" style="backdrop-filter: blur(5px);">
        <div class="relative max-w-3xl max-h-screen p-4">
            <button onclick="closeImageModal()" class="absolute -top-10 right-0 text-white text-3xl hover:text-gray-300 focus:outline-none">
                <i class="fas fa-times"></i>
            </button>
            <img id="modalImage" src="" alt="Ticket" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl border-4 border-white">
        </div>
    </div>

    <script>
        function openImageModal(url) {
            const modal = document.getElementById('imageModal');
            const img = document.getElementById('modalImage');
            img.src = url;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeImageModal();
        });
        
        // Cerrar al click fuera
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) closeImageModal();
        });
    </script>
</x-app-layout>