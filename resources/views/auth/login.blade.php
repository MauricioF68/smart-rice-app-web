<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - SmartRice</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- RESET Y VARIABLES --- */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Nunito', sans-serif; }
        
        :root {
            --color-primary: #5F7F3B;   /* Verde Campo */
            --color-secondary: #B79C43; /* Amarillo Arroz */
            --color-bg: #F5F5F5;
            --color-text: #333;
        }

        body {
            background-color: var(--color-bg);
            /* Fondo con un toque sutil */
            background-image: radial-gradient(#dcfce7 1px, transparent 1px);
            background-size: 20px 20px;
            
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }

        /* --- TARJETA DE LOGIN --- */
        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
            border-top: 5px solid var(--color-primary);
        }

        /* --- LOGO Y ENCABEZADO --- */
        .brand-logo {
            font-size: 3rem;
            color: var(--color-primary);
            margin-bottom: 10px;
            display: inline-block;
        }

        .title {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--color-text);
            margin-bottom: 5px;
        }

        .subtitle {
            color: #777;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* --- FORMULARIO --- */
        .form-group {
            margin-bottom: 1.2rem;
            text-align: left;
        }

        .input-label {
            display: block;
            margin-bottom: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            color: #555;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .form-input {
            width: 100%;
            padding: 12px 12px 12px 40px; /* Espacio para el icono */
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s;
            outline: none;
        }

        .form-input:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1);
        }

        /* --- BOTÓN --- */
        .btn-login {
            background-color: var(--color-primary);
            color: white;
            width: 100%;
            padding: 12px;
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

        .btn-login:hover {
            background-color: #4b662e;
        }

        /* --- EXTRAS --- */
        .options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            margin-top: 10px;
        }

        .forgot-link {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 600;
        }
        .forgot-link:hover { text-decoration: underline; }

        .back-link {
            display: block;
            margin-top: 2rem;
            color: #888;
            text-decoration: none;
            font-size: 0.9rem;
        }
        .back-link:hover { color: var(--color-primary); }

        .error-msg {
            color: #dc3545;
            font-size: 0.8rem;
            margin-top: 5px;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="login-card">
        
        <div class="brand-logo">
            <i class="fa-solid fa-wheat-awn"></i>
        </div>
        
        <h1 class="title">Bienvenido</h1>
        <p class="subtitle">Ingresa a SmartRice para gestionar tu producción</p>

        @if (session('status'))
            <div style="color: var(--color-primary); margin-bottom: 15px; font-weight: bold;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="input-label">Correo Electrónico</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-envelope input-icon"></i>
                    <input id="email" type="email" name="email" class="form-input" 
                           value="{{ old('email') }}" required autofocus 
                           placeholder="ej. usuario@smartrice.com">
                </div>
                @error('email')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="input-label">Contraseña</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <input id="password" type="password" name="password" class="form-input" 
                           required autocomplete="current-password" 
                           placeholder="••••••••">
                </div>
                @error('password')
                    <div class="error-msg">{{ $message }}</div>
                @enderror
            </div>

            <div class="options">
                <label style="display: flex; align-items: center; cursor: pointer; color: #555;">
                    <input type="checkbox" name="remember" style="margin-right: 5px;">
                    Recordarme
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-login">
                Ingresar al Sistema <i class="fa-solid fa-arrow-right-to-bracket"></i>
            </button>
        </form>

        <a href="{{ url('/') }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Volver al inicio
        </a>
    </div>

</body>
</html>
