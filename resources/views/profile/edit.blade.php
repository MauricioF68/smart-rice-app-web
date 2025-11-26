<x-app-layout>
    
    <style>
        /* 1. Forzar fondo blanco en los inputs */
        input[type="text"], 
        input[type="email"], 
        input[type="password"] {
            background-color: #fff !important;
            color: #333 !important;
            border: 1px solid #d1d5db !important;
            border-radius: 8px !important;
            padding: 10px 15px !important;
            outline: none !important;
            box-shadow: none !important;
            transition: all 0.3s !important;
        }

        /* 2. Efecto Foco Verde al escribir */
        input:focus {
            border-color: var(--color-primary) !important;
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1) !important;
        }

        /* 3. Títulos y Textos */
        header h2 {
            color: var(--color-text-main) !important;
            font-weight: 800 !important;
        }
        p {
            color: #666 !important;
        }

        /* 4. Botones de Guardar (Transformarlos a Verde) */
        button[type="submit"], .inline-flex {
            background-color: var(--color-primary) !important;
            color: white !important;
            padding: 10px 20px !important;
            border-radius: 6px !important;
            font-weight: 700 !important;
            border: none !important;
            text-transform: none !important;
            transition: background 0.3s !important;
        }
        
        button[type="submit"]:hover {
            background-color: #4b662e !important;
        }

        /* 5. Botón de Eliminar Cuenta (Rojo) */
        .text-red-600 { color: #dc2626 !important; font-weight: bold; }
        .bg-red-600 { background-color: #dc2626 !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mi Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-xl border border-gray-100">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-lg sm:rounded-xl border border-red-100">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>