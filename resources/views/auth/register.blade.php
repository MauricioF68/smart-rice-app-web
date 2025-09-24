<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" x-data="{ rol: 'agricultor' }">
        @csrf

        <div>
            <x-input-label for="rol" :value="__('Tipo de Cuenta')" />
            <div class="flex items-center justify-around mt-2 p-2 bg-gray-100 dark:bg-gray-800 rounded-md">
                <label for="rol_agricultor" class="flex items-center cursor-pointer">
                    <input x-model="rol" type="radio" id="rol_agricultor" name="rol" value="agricultor" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Agricultor') }}</span>
                </label>
                <label for="rol_molino" class="flex items-center cursor-pointer">
                    <input x-model="rol" type="radio" id="rol_molino" name="rol" value="molino" class="text-indigo-600 focus:ring-indigo-500">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Molino') }}</span>
                </label>
            </div>
        </div>

        <div x-show="rol === 'agricultor'" x-transition class="mt-4 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <x-input-label for="primer_nombre" :value="__('Primer Nombre')" />
                    <x-text-input id="primer_nombre" class="block mt-1 w-full" type="text" name="primer_nombre" :value="old('primer_nombre')" autofocus />
                    <x-input-error :messages="$errors->get('primer_nombre')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="segundo_nombre" :value="__('Segundo Nombre (Opcional)')" />
                    <x-text-input id="segundo_nombre" class="block mt-1 w-full" type="text" name="segundo_nombre" :value="old('segundo_nombre')" />
                    <x-input-error :messages="$errors->get('segundo_nombre')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="apellido_paterno" :value="__('Apellido Paterno')" />
                    <x-text-input id="apellido_paterno" class="block mt-1 w-full" type="text" name="apellido_paterno" :value="old('apellido_paterno')" />
                    <x-input-error :messages="$errors->get('apellido_paterno')" class="mt-2" />
                </div>
                <div>
                    <x-input-label for="apellido_materno" :value="__('Apellido Materno')" />
                    <x-text-input id="apellido_materno" class="block mt-1 w-full" type="text" name="apellido_materno" :value="old('apellido_materno')" />
                    <x-input-error :messages="$errors->get('apellido_materno')" class="mt-2" />
                </div>
            </div>
            <div>
                <x-input-label for="dni" :value="__('DNI')" />
                <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni" :value="old('dni')" />
                <x-input-error :messages="$errors->get('dni')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="codigo_de_agricultor" :value="__('Código de Agricultor')" />
                <x-text-input id="codigo_de_agricultor" class="block mt-1 w-full" type="text" name="codigo_de_agricultor" :value="old('codigo_de_agricultor')" />
                <x-input-error :messages="$errors->get('codigo_de_agricultor')" class="mt-2" />
            </div>
        </div>

        <div x-show="rol === 'molino'" x-transition class="mt-4 space-y-4">
            <div>
                <x-input-label for="razon_social" :value="__('Razón Social')" />
                <x-text-input id="razon_social" class="block mt-1 w-full" type="text" name="razon_social" :value="old('razon_social')" />
                <x-input-error :messages="$errors->get('razon_social')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="nombre_comercial" :value="__('Nombre Comercial (Opcional)')" />
                <x-text-input id="nombre_comercial" class="block mt-1 w-full" type="text" name="nombre_comercial" :value="old('nombre_comercial')" />
                <x-input-error :messages="$errors->get('nombre_comercial')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="ruc" :value="__('RUC')" />
                <x-text-input id="ruc" class="block mt-1 w-full" type="text" name="ruc" :value="old('ruc')" />
                <x-input-error :messages="$errors->get('ruc')" class="mt-2" />
            </div>
        </div>
        
        <div class="mt-4 space-y-4">
             <div>
                <x-input-label for="telefono" :value="__('Teléfono')" />
                <x-text-input id="telefono" class="block mt-1 w-full" type="text" name="telefono" :value="old('telefono')" />
                <x-input-error :messages="$errors->get('telefono')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="direccion" :value="__('Dirección')" />
                <x-text-input id="direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" />
                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Contraseña')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('¿Ya estás registrado?') }}
            </a>
            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>