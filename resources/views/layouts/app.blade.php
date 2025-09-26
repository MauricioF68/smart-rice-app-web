<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            
            {{-- Estructura de dos columnas: Sidebar + Contenido Principal --}}
            <div class="flex">
                
                {{-- AQUÍ ESTÁ LA LÓGICA PARA LLAMAR AL SIDEBAR CORRECTO --}}
                @if (auth()->user()->rol === 'agricultor')
                    @include('layouts.partials._agricultor-sidebar')
                @elseif (auth()->user()->rol === 'molino')
                    @include('layouts.partials._molino-sidebar')
                @endif
                
                <div class="flex-1 flex flex-col">
                    @include('layouts.navigation')

                    @if (isset($header))
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endif

                    <main class="flex-1 overflow-y-auto">
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
                             @if (session('status'))
                                <div class="alert alert--success mb-4">
                                    {{ session('status') }}
                                </div>
                            @endif
                            {{ $slot }}
                        </div>
                    </main>
                </div>
            </div>

        </div>
    </body>
</html>