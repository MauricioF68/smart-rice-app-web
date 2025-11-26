<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SmartRice - Gestión Agrícola</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,600,700,800&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Estructura Básica */
        body {
            background-color: var(--color-bg-body);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        /* --- NAVBAR --- */
        .navbar {
            background-color: white;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-logo {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--color-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-links {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .nav-link-text {
            text-decoration: none;
            color: var(--color-text-main);
            font-weight: 700;
            font-size: 0.95rem;
            transition: color 0.3s;
            padding: 5px 10px;
        }
        .nav-link-text:hover { color: var(--color-primary); }

        /* --- HERO SECTION --- */
        .hero-section {
            background: linear-gradient(135deg, var(--color-primary) 0%, #3e5227 100%);
            color: white;
            padding: 5rem 2rem;
            text-align: center;
            border-bottom-right-radius: 50px;
            border-bottom-left-radius: 50px;
            margin-bottom: 2rem;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2.5rem auto;
            opacity: 0.9;
            font-weight: 400;
        }

        /* --- CARDS / FEATURES --- */
        .features-container {
            max-width: 1200px;
            margin: -4rem auto 3rem auto;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            text-align: center;
            transition: transform 0.3s ease;
            border-bottom: 4px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            border-bottom: 4px solid var(--color-secondary);
        }

        .icon-box {
            background-color: #f0fdf4;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            color: var(--color-primary);
            font-size: 1.8rem;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--color-text-main);
        }

        .card-text {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        /* --- FOOTER --- */
        footer {
            margin-top: auto;
            background-color: #f8f9fa;
            text-align: center;
            padding: 2rem;
            font-size: 0.9rem;
            color: #666;
            border-top: 1px solid #eee;
        }

        /* --- RESPONSIVE MOBILE (Importante) --- */
        @media (max-width: 768px) {
            /* Navbar en columna */
            .navbar {
                flex-direction: column;
                gap: 1.5rem;
                padding: 1.5rem;
            }
            
            .nav-links {
                width: 100%;
                flex-direction: column;
                gap: 10px;
            }

            /* Botones full width en celular */
            .nav-links a {
                width: 100%;
                text-align: center;
                display: block;
            }

            /* Ajustes de texto */
            .hero-title { font-size: 2rem; }
            .hero-section { padding: 3rem 1.5rem; border-radius: 0 0 30px 30px; }
            .features-container { margin-top: 1rem; }
        }
    </style>
</head>
<body class="antialiased">

    <nav class="navbar">
        <a href="#" class="brand-logo">
            <i class="fa-solid fa-wheat-awn"></i> smartRice
        </a>

        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-action btn-confirm">
                        <i class="fa-solid fa-gauge"></i> Ir al Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link-text">
                        <i class="fa-solid fa-right-to-bracket"></i> Iniciar Sesión
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn-action btn-edit">
                            <i class="fa-solid fa-user-plus"></i> Registrarse
                        </a>
                    @endif

                @endauth
            @endif
        </div>
    </nav>

    <header class="hero-section">
        <h1 class="hero-title">Gestión Inteligente para<br>la Cadena de Arroz</h1>
        <p class="hero-subtitle">
            Conecta agricultores, molinos y casetas de control.
            Trazabilidad, liquidaciones y calidad en tiempo real.
        </p>
        
        @if (Route::has('login'))
            @auth
                <p style="font-weight: bold; font-size: 1.1rem;">👋 ¡Hola de nuevo!</p>
            @else
                <a href="{{ route('login') }}" class="btn-action" style="background: white; color: var(--color-primary); padding: 12px 30px; font-size: 1.1rem;">
                    Ingresar al Sistema <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i>
                </a>
            @endauth
        @endif
    </header>

    <section class="features-container">
        <div class="feature-card">
            <div class="icon-box"><i class="fa-solid fa-tractor"></i></div>
            <h3 class="card-title">Para Agricultores</h3>
            <p class="card-text">Consulta lotes, liquidaciones y notificaciones en tu celular.</p>
        </div>

        <div class="feature-card">
            <div class="icon-box"><i class="fa-solid fa-industry"></i></div>
            <h3 class="card-title">Gestión de Molino</h3>
            <p class="card-text">Controla recepción, laboratorio y pagos eficientemente.</p>
        </div>

        <div class="feature-card">
            <div class="icon-box"><i class="fa-solid fa-flask"></i></div>
            <h3 class="card-title">Control de Calidad</h3>
            <p class="card-text">Análisis de humedad e impurezas con trazabilidad total.</p>
        </div>
    </section>

    <footer>
        <p>&copy; {{ date('Y') }} <strong>SmartRice</strong>. MVP v1.0</p>
    </footer>

</body>
</html>