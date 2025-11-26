<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - SmartRice</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- TUS ESTILOS PERSONALIZADOS --- */
        [x-cloak] { display: none !important; } /* Oculta elementos antes de que Alpine cargue */

        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Nunito', sans-serif; }
        
        :root {
            --color-primary: #5F7F3B;   /* Verde Campo */
            --color-secondary: #B79C43; /* Amarillo Arroz */
            --color-bg: #F5F5F5;
            --color-text: #333;
            --color-border: #ddd;
        }

        body {
            background-color: var(--color-bg);
            background-image: radial-gradient(#dcfce7 1px, transparent 1px);
            background-size: 20px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .register-card {
            background: white;
            width: 100%;
            max-width: 700px;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-top: 5px solid var(--color-secondary);
        }

        .header { text-align: center; margin-bottom: 2rem; }
        .brand-logo { font-size: 2.5rem; color: var(--color-primary); margin-bottom: 10px; }
        .title { font-size: 1.8rem; font-weight: 800; color: var(--color-text); }
        .subtitle { color: #777; font-size: 0.95rem; }

        /* --- SELECTOR DE ROL --- */
        .role-selector {
            display: flex;
            gap: 15px;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .role-option {
            flex: 1;
            position: relative;
        }

        /* Input invisible pero clickeable */
        .role-option input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            height: 100%;
            width: 100%;
            z-index: 10; /* Asegura que esté por encima */
            left: 0;
            top: 0;
        }

        .role-box {
            border: 2px solid var(--color-border);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background: #fff;
            position: relative;
            z-index: 1;
        }

        .role-box i { font-size: 1.5rem; display: block; margin-bottom: 5px; color: #999; }
        .role-box span { font-weight: 700; color: #777; }

        /* Estilos cuando el radio está seleccionado */
        /* Alpine pone la clase, o el selector CSS nativo :checked funciona si el input está bien ubicado */
        .role-option input:checked + .role-box {
            border-color: var(--color-primary);
            background-color: #f0fdf4;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(95, 127, 59, 0.2);
        }
        .role-option input:checked + .role-box i,
        .role-option input:checked + .role-box span {
            color: var(--color-primary);
        }

        /* --- FORMULARIOS --- */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        @media (max-width: 600px) { .form-grid { grid-template-columns: 1fr; } }

        .form-group { margin-bottom: 1.2rem; }
        .input-label { display: block; margin-bottom: 6px; font-weight: 700; font-size: 0.9rem; color: #555; }
        .input-wrapper { position: relative; }
        .input-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #999; width: 20px; text-align: center; }
        
        .form-input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--color-secondary);
            box-shadow: 0 0 0 3px rgba(183, 156, 67, 0.1);
        }

        .btn-register {
            background-color: var(--color-primary);
            color: white;
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-register:hover { background-color: #4b662e; }

        .login-link-container { text-align: center; margin-top: 1.5rem; font-size: 0.9rem; }
        .login-link { color: var(--color-primary); font-weight: 700; text-decoration: none; }
        .login-link:hover { text-decoration: underline; }
        .error-msg { color: #dc3545; font-size: 0.8rem; margin-top: 5px; font-weight: 600; display: block;}
    </style>
</head>
<body>

    <div class="register-card">
        
        <div class="header">
            <div class="brand-logo"><i class="fa-solid fa-wheat-awn"></i></div>
            <h1 class="title">Crear Cuenta</h1>
            <p class="subtitle">Únete a la plataforma SmartRice</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="{ rol: '{{ old('rol', 'agricultor') }}' }">
            @csrf

            <label class="input-label" style="text-align: center; margin-bottom: 10px;">Selecciona tu perfil:</label>
            <div class="role-selector">
                <div class="role-option">
                    <input 
                        x-model="rol" 
                        type="radio" 
                        name="rol" 
                        value="agricultor"
                        id="rol_agricultor"
                        {{ old('rol', 'agricultor') == 'agricultor' ? 'checked' : '' }}
                    >
                    <div class="role-box" @click="rol = 'agricultor'">
                        <i class="fa-solid fa-tractor"></i>
                        <span>Agricultor</span>
                    </div>
                </div>
                <div class="role-option">
                    <input 
                        x-model="rol" 
                        type="radio" 
                        name="rol" 
                        value="molino"
                        id="rol_molino"
                        {{ old('rol') == 'molino' ? 'checked' : '' }}
                    >
                    <div class="role-box" @click="rol = 'molino'">
                        <i class="fa-solid fa-industry"></i>
                        <span>Molino</span>
                    </div>
                </div>
            </div>

            <div x-show="rol === 'agricultor'" x-transition:enter.duration.300ms x-cloak>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="input-label">Primer Nombre</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user input-icon"></i>
                            <input type="text" name="primer_nombre" class="form-input" value="{{ old('primer_nombre') }}" placeholder="Juan">
                        </div>
                        @error('primer_nombre') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Segundo Nombre (Opcional)</label>
                        <div class="input-wrapper">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" name="segundo_nombre" class="form-input" value="{{ old('segundo_nombre') }}" placeholder="Carlos">
                        </div>
                        @error('segundo_nombre') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Apellido Paterno</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user-tag input-icon"></i>
                            <input type="text" name="apellido_paterno" class="form-input" value="{{ old('apellido_paterno') }}" placeholder="Pérez">
                        </div>
                        @error('apellido_paterno') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Apellido Materno</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-user-tag input-icon"></i>
                            <input type="text" name="apellido_materno" class="form-input" value="{{ old('apellido_materno') }}" placeholder="Gómez">
                        </div>
                        @error('apellido_materno') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="input-label">DNI</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-address-card input-icon"></i>
                            <input type="text" name="dni" class="form-input" value="{{ old('dni') }}" placeholder="12345678">
                        </div>
                        @error('dni') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="input-label">Código Agricultor</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-barcode input-icon"></i>
                            <input type="text" name="codigo_de_agricultor" class="form-input" value="{{ old('codigo_de_agricultor') }}" placeholder="AGR-001">
                        </div>
                        @error('codigo_de_agricultor') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div x-show="rol === 'molino'" x-transition:enter.duration.300ms x-cloak>
                <div class="form-group">
                    <label class="input-label">Razón Social</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-building input-icon"></i>
                        <input type="text" name="razon_social" class="form-input" value="{{ old('razon_social') }}" placeholder="Molino del Norte S.A.C.">
                    </div>
                    @error('razon_social') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Nombre Comercial (Opcional)</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-store input-icon"></i>
                        <input type="text" name="nombre_comercial" class="form-input" value="{{ old('nombre_comercial') }}" placeholder="Arroz Norteño">
                    </div>
                    @error('nombre_comercial') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">RUC</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-file-invoice input-icon"></i>
                        <input type="text" name="ruc" class="form-input" value="{{ old('ruc') }}" placeholder="20123456789">
                    </div>
                    @error('ruc') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

            <div class="form-grid">
                <div class="form-group">
                    <label class="input-label">Teléfono</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-phone input-icon"></i>
                        <input type="text" name="telefono" class="form-input" value="{{ old('telefono') }}" placeholder="999 999 999">
                    </div>
                    @error('telefono') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Dirección</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-map-marker-alt input-icon"></i>
                        <input type="text" name="direccion" class="form-input" value="{{ old('direccion') }}" placeholder="Av. Principal 123">
                    </div>
                    @error('direccion') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="input-label">Correo Electrónico</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autocomplete="username" placeholder="usuario@smartrice.com">
                </div>
                @error('email') <span class="error-msg">{{ $message }}</span> @enderror
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="input-label">Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password" class="form-input" required autocomplete="new-password" placeholder="••••••••">
                    </div>
                    @error('password') <span class="error-msg">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="input-label">Confirmar Contraseña</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock input-icon"></i>
                        <input type="password" name="password_confirmation" class="form-input" required autocomplete="new-password" placeholder="••••••••">
                    </div>
                    @error('password_confirmation') <span class="error-msg">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="btn-register">
                Registrarse <i class="fa-solid fa-user-plus"></i>
            </button>

            <div class="login-link-container">
                ¿Ya tienes una cuenta? 
                <a href="{{ route('login') }}" class="login-link">Inicia sesión aquí</a>
            </div>

        </form>
    </div>

</body>
</html>