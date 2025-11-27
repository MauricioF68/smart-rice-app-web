<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selección de Caseta - SmartRice</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary: #5F7F3B; /* Verde SmartRice */
            --color-primary-hover: #4b662e;
            --color-accent: #B79C43; /* Amarillo Trigo */
        }

        body {
            background-color: #f3f4f6; /* Fondo gris claro limpio */
            font-family: 'Figtree', sans-serif;
        }

        /* Estilos personalizados para los Radio Buttons */
        .caseta-radio:checked + div {
            border-color: var(--color-primary);
            background-color: #f0fdf4; /* Verde muy pálido */
            box-shadow: 0 4px 6px -1px rgba(95, 127, 59, 0.2);
        }

        .caseta-radio:checked + div .icon-container {
            background-color: var(--color-primary);
            color: white;
        }

        .caseta-radio:checked + div .check-icon {
            opacity: 1;
            transform: scale(1);
        }

        .input-code {
            letter-spacing: 0.25em;
            font-size: 1.25rem;
            text-align: center;
            text-transform: uppercase;
            border: 2px solid #e5e7eb;
            transition: all 0.3s;
        }
        
        .input-code:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 4px rgba(95, 127, 59, 0.1);
            outline: none;
        }

        .btn-smartrice {
            background-color: var(--color-primary);
            transition: all 0.3s;
        }
        .btn-smartrice:hover {
            background-color: var(--color-primary-hover);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-center items-center py-12 sm:px-6 lg:px-8">

    {{-- LOGO SUPERIOR --}}
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
        <div class="flex justify-center items-center gap-3 text-3xl font-bold text-gray-800">
            <i class="fa-solid fa-wheat-awn" style="color: #B79C43;"></i>
            <span style="color: #5F7F3B;">Smart</span>Rice
        </div>
        <h2 class="mt-4 text-center text-xl font-bold text-gray-700">
            Inicio de Turno
        </h2>
        <p class="mt-1 text-center text-sm text-gray-500">
            Bienvenido, {{ Auth::user()->primer_nombre }} {{ Auth::user()->apellido_paterno }}
        </p>
    </div>

    {{-- TARJETA PRINCIPAL --}}
    <div class="mt-2 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl shadow-gray-200 sm:rounded-xl sm:px-10 border border-gray-100">
            
            <form method="POST" action="{{ route('caseta.set') }}">
                @csrf

                {{-- ERRORES GLOBALES --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 font-bold">Error de acceso</p>
                                <p class="text-xs text-red-600 mt-1">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- SECCIÓN 1: ELEGIR CASETA --}}
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide text-xs">
                        1. Selecciona tu ubicación
                    </label>
                    
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                        @foreach($casetas as $caseta)
                            <label class="relative block cursor-pointer group">
                                <input type="radio" name="caseta_id" value="{{ $caseta->id }}" class="peer sr-only caseta-radio" required>
                                
                                <div class="p-4 rounded-xl border-2 border-gray-100 hover:border-green-300 hover:bg-green-50 transition-all flex items-center justify-between">
                                    <div class="flex items-center">
                                        {{-- Icono Circular --}}
                                        <div class="icon-container h-10 w-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center transition-colors">
                                            <i class="fas fa-warehouse"></i>
                                        </div>
                                        
                                        {{-- Textos --}}
                                        <div class="ml-4">
                                            <h3 class="font-bold text-gray-800">{{ $caseta->nombre }}</h3>
                                            <p class="text-xs text-gray-500 flex items-center mt-0.5">
                                                <i class="fas fa-map-marker-alt mr-1 text-gray-400"></i> {{ $caseta->ubicacion ?? 'Sin ubicación' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Check Icon (Animado) --}}
                                    <div class="check-icon opacity-0 transform scale-50 transition-all duration-300 text-[#5F7F3B] text-xl">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- SECCIÓN 2: CÓDIGO --}}
                <div class="mb-6 pt-6 border-t border-gray-100">
                    <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide text-xs">
                        2. Código de Seguridad de Caseta
                    </label>
                    
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="codigo_seguridad" 
                               class="input-code w-full pl-10 pr-3 py-3 rounded-lg" 
                               placeholder="CÓDIGO"
                               autocomplete="off"
                               required>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">Ingresa el código visible en la caseta.</p>
                </div>

                {{-- BOTÓN --}}
                <div>
                    <button type="submit" class="btn-smartrice w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Validar e Ingresar
                    </button>
                </div>

            </form>

            {{-- FOOTER --}}
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            ¿No es tu turno?
                        </span>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="font-medium text-gray-400 hover:text-red-500 transition">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

</body>
</html>