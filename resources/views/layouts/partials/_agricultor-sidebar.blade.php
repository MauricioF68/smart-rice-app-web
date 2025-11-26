<aside class="sidebar">
    
    <style>
        .sidebar {
            width: 260px;
            min-height: 100vh; /* Altura completa */
            background-color: #5F7F3B; /* Verde SmartRice (Hardcoded para seguridad) */
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar__logo {
            padding: 1.5rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.05); /* Un poco más oscuro para separar */
            margin-bottom: 1rem;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar__nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar__nav li {
            width: 100%;
        }

        .sidebar__nav a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8); /* Blanco con un poco de transparencia */
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-left: 5px solid transparent; /* Para el efecto activo */
        }

        .sidebar__nav a i {
            width: 25px; /* Ancho fijo para alinear texto */
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
        }

        /* Efecto Hover (Pasar el mouse) */
        .sidebar__nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 30px; /* Pequeño movimiento a la derecha */
        }

        /* Estado Activo (Página actual) */
        .sidebar__nav a.active {
            background-color: #f5f5f5; /* Fondo claro para resaltar */
            color: #5F7F3B; /* Texto verde */
            border-left-color: #B79C43; /* Borde Amarillo Arroz */
        }
    </style>

    <div class="sidebar__logo">
        <a href="{{ route('dashboard') }}" class="logo-text">
            <i class="fa-solid fa-wheat-awn" style="color: #B79C43;"></i> SmartRice
        </a>
    </div>

    <nav class="sidebar__nav">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </li>
            
            <li>
                <a href="{{ route('preventas.index') }}" class="{{ request()->routeIs('preventas.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-dollar"></i> Mis Preventas
                </a>
            </li>
            
            <li>
                <a href="{{ route('preventas.negociaciones') }}" class="{{ request()->routeIs('preventas.negociaciones') ? 'active' : '' }}">
                    <i class="fas fa-comments-dollar"></i> Mis Negociaciones
                </a>
            </li>
            
            <li>
                <a href="{{ route('lotes.index') }}" class="{{ request()->routeIs('lotes.*') ? 'active' : '' }}">
                    <i class="fas fa-map-location-dot"></i> Mis Lotes
                </a>
            </li>           
            
            <li>
                <a href="{{ route('campanas.mercado') }}" class="{{ request()->routeIs('campanas.mercado') ? 'active' : '' }}">
                    <i class="fas fa-store"></i> Mercado de Campañas
                </a>
            </li>
            
            <li>
                <a href="#" class="{{ request()->routeIs('historial.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Historial de Ventas
                </a>
            </li>
            
            <li>
                <a href="{{ route('cuentas-bancarias.index') }}" class="{{ request()->routeIs('cuentas-bancarias.*') ? 'active' : '' }}">
                    <i class="fas fa-university"></i> Cuentas Bancarias
                </a>
            </li>
            
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> Mi Perfil
                </a>
            </li>

            <li style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #ffcccc;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</aside>