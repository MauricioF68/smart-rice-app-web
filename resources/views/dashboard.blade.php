<x-app-layout>
    {{-- Ya no usamos el <x-slot name="header"> para que el diseño sea más limpio e integrado --}}

    {{-- Este es el contenido que irá directamente en el <main> de tu plantilla --}}
    
    <div class="flex justify-between items-center mb-6">
        {{-- Saludo personalizado que funciona para agricultor o molino --}}
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            Bienvenido, {{ Auth::user()->primer_nombre ?? Auth::user()->name }}
        </h1>

        {{-- Lógica para mostrar el botón correcto según el rol --}}
        @if(auth()->user()->rol === 'agricultor')
            <a href="{{ route('preventas.create') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Crear Nueva Preventa
            </a>
        @elseif(auth()->user()->rol === 'molino')
            <a href="#" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                <i class="fas fa-plus mr-2"></i> Crear Nueva Campaña
            </a>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Tarjeta 1: Preventas Activas --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-gray-500 dark:text-gray-400">Preventas Activas</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $preventasActivas }}</p>
        </div>
        
        {{-- Tarjeta 2: Propuestas Recibidas --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-gray-500 dark:text-gray-400">Propuestas Recibidas</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $propuestasRecibidas }}</p>
        </div>
        
        {{-- Tarjeta 3: Ventas Completadas --}}
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h3 class="text-gray-500 dark:text-gray-400">Ventas Completadas</h3>
            <p class="text-3xl font-bold mt-2 text-gray-900 dark:text-gray-100">{{ $ventasCompletadas }}</p>
        </div>

    </div>

</x-app-layout>