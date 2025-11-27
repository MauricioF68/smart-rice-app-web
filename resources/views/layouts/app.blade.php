<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SmartRice') }}</title>

        <!-- Fuentes -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=nunito:400,600,700,800&display=swap" rel="stylesheet" />
        
        <!-- Iconos -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Estilos y Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    
    <body class="font-sans antialiased">
        <!-- 
           CONTENEDOR PRINCIPAL
           Usamos nuestras variables CSS para asegurar el color de fondo correcto 
        -->
        <div class="min-h-screen" style="background-color: var(--color-bg-body); color: var(--color-text-main);">
            
            <div class="flex">
                
                {{-- SIDEBAR (BARRA LATERAL) --}}
                {{-- Nota: Si la barra lateral sigue saliendo oscura, tendremos que editar 
                     los archivos '_agricultor-sidebar' y '_molino-sidebar' después --}}
                @auth
                    @if (auth()->user()->rol === 'agricultor')
                        @include('layouts.partials._agricultor-sidebar')

                    @elseif (auth()->user()->rol === 'molino')
                        @include('layouts.partials._molino-sidebar')

                    {{-- NUEVO: Validación para Administrador --}}
                    {{-- Comprobamos 'admin' o 'administrador' por seguridad --}}
                    @elseif (auth()->user()->rol === 'admin' || auth()->user()->rol === 'administrador')
                        @include('layouts.partials._administrador-sidebar')
                    @endif
                @endauth
                
                {{-- CONTENIDO DERECHO (Navbar + Main) --}}
                <div class="flex-1 flex flex-col min-h-screen">
                    
                    {{-- NAVBAR SUPERIOR (Barra de usuario) --}}
                    @include('layouts.navigation')

                    {{-- HEADER (Título de la página actual) --}}
                    @if (isset($header))
                        <header class="bg-white shadow-sm border-b border-gray-200">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    {{-- MAIN (El contenido real del Dashboard) --}}
                    <main class="flex-1 overflow-y-auto p-6">
                        <div class="max-w-7xl mx-auto">
                            
                            {{-- Mensajes de Estado (Alertas Globales) --}}
                            @if (session('status'))
                                <div class="mb-6 p-4 rounded-lg bg-green-100 text-green-800 border border-green-200 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i> {{ session('status') }}
                                </div>
                            @endif

                            {{-- Aquí se inyecta el Dashboard --}}
                            {{ $slot }}
                        </div>
                    </main>

                </div>
            </div>

        </div>
    </body>
</html>