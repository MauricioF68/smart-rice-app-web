<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Operativo') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Bienvenida --}}
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Hola, {{ Auth::user()->primer_nombre }} 👋</h3>
                <p class="text-gray-500">Resumen de operaciones en <strong>{{ $caseta->nombre }}</strong>.</p>
            </div>

            {{-- TARJETAS DE MÉTRICAS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                {{-- Tarjeta 1: Solicitudes Pactadas --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-blue-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-file-contract text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold uppercase">Solicitudes Pactadas</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $solicitudesPactadas }}</p>
                            <p class="text-xs text-gray-400">Pendientes de ingreso en el sistema</p>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 2: Procesados Hoy --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-green-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-check-double text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold uppercase">Procesados Hoy</p>
                            <p class="text-3xl font-bold text-gray-800">{{ $procesadosHoy }}</p>
                            <p class="text-xs text-gray-400">Camiones atendidos en tu turno</p>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta 3: Incidencias (Ejemplo) --}}
                <div class="bg-white overflow-hidden shadow-lg sm:rounded-xl border-l-4 border-yellow-500 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-exclamation-triangle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm font-bold uppercase">Incidencias</p>
                            <p class="text-3xl font-bold text-gray-800">0</p>
                            <p class="text-xs text-gray-400">Rechazos o problemas</p>
                        </div>
                    </div>
                </div>

            </div>
            
            {{-- ACCESO RÁPIDO --}}
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100 flex justify-between items-center">
                <div>
                    <h4 class="font-bold text-lg text-gray-800">¿Llegó un camión?</h4>
                    <p class="text-gray-500 text-sm">Ve directamente al módulo de recepción para buscar la solicitud.</p>
                </div>
                <a href="{{ route('caseta.recepcion') }}" class="px-6 py-3 bg-[#5F7F3B] text-white font-bold rounded-lg hover:bg-[#4b662e] transition shadow">
                    Ir a Recepción <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>