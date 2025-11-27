<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Liquidaciones') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @forelse ($pagos as $item)
                {{-- Lógica para obtener nombre del Molino --}}
                @php
                    $propuesta = $item->propuestas->first();
                    $molino = $propuesta ? ($propuesta->user->razon_social ?? $propuesta->user->name) : 'Empresa';
                @endphp

                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl mb-6 border border-gray-100">
                    <div class="p-6 flex flex-col md:flex-row justify-between items-center gap-6">
                        
                        {{-- IZQUIERDA: Info del Pago --}}
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded border border-green-200 uppercase tracking-wide mr-3">
                                    Pagado
                                </span>
                                <span class="text-sm text-gray-500">
                                    {{ $item->pago->created_at->format('d M Y, h:i A') }}
                                </span>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-gray-800 mb-1">
                                S/ {{ number_format($item->pago->monto_total, 2) }}
                            </h3>
                            
                            <p class="text-gray-600 text-sm">
                                Pagado por: <strong>{{ $molino }}</strong>
                            </p>
                            
                            <div class="mt-3 text-sm text-gray-500 bg-gray-50 p-3 rounded-lg inline-block border border-gray-200">
                                <p><i class="fas fa-university mr-1"></i> Banco: {{ $item->pago->banco_origen }}</p>
                                <p><i class="fas fa-hashtag mr-1"></i> Op: {{ $item->pago->numero_operacion }}</p>
                            </div>
                        </div>

                        {{-- DERECHA: Botón Ver Voucher --}}
                        <div>
                            @if($item->pago->foto_voucher)
                                <button onclick="openVoucherModal('{{ asset('storage/' . $item->pago->foto_voucher) }}')" 
                                        class="flex flex-col items-center justify-center w-full md:w-48 h-32 border-2 border-dashed border-green-300 rounded-xl bg-green-50 hover:bg-green-100 transition cursor-pointer group">
                                    <i class="fas fa-file-invoice-dollar text-3xl text-green-600 mb-2 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-sm font-bold text-green-700">Ver Voucher</span>
                                    <span class="text-xs text-green-600">Clic para ampliar</span>
                                </button>
                            @else
                                <span class="text-gray-400 italic">Sin imagen</span>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 mb-4">
                        <i class="fas fa-wallet text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900">Aún no tienes liquidaciones</h3>
                    <p class="text-gray-500 mt-2">Cuando un molino registre tu pago, aparecerá aquí.</p>
                </div>
            @endforelse

        </div>
    </div>

    {{-- MODAL PARA VER FOTO (Reutilizable) --}}
    <div id="voucherModal" class="fixed inset-0 bg-black bg-opacity-90 hidden items-center justify-center z-50 transition-opacity backdrop-blur-sm">
        <div class="relative max-w-4xl w-full p-4 flex flex-col items-center">
            
            <button onclick="closeVoucherModal()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 focus:outline-none z-50">
                &times;
            </button>
            
            <img id="modalVoucherImage" src="" alt="Voucher" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl">
            
            <a id="downloadLink" href="" download class="mt-4 px-6 py-2 bg-white text-gray-800 font-bold rounded-full hover:bg-gray-200 transition">
                <i class="fas fa-download mr-2"></i> Descargar Imagen
            </a>
        </div>
    </div>

    <script>
        function openVoucherModal(url) {
            const modal = document.getElementById('voucherModal');
            const img = document.getElementById('modalVoucherImage');
            const link = document.getElementById('downloadLink');
            
            img.src = url;
            link.href = url; // Para que el botón de descarga funcione
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeVoucherModal() {
            const modal = document.getElementById('voucherModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Cerrar con tecla ESC
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeVoucherModal();
        });
        
        // Cerrar al click fuera
        document.getElementById('voucherModal').addEventListener('click', function(e) {
            if (e.target === this) closeVoucherModal();
        });
    </script>
</x-app-layout>