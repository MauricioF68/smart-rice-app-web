<aside class="sidebar">
    
    <style>
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background-color: #5F7F3B; /* Verde SmartRice */
            color: white;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar__logo {
            padding: 1.5rem;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.05);
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

        .sidebar__nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar__nav li { width: 100%; }

        .sidebar__nav a {
            display: flex;
            align-items: center;
            padding: 15px 25px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border-left: 5px solid transparent;
        }

        .sidebar__nav a i {
            width: 25px;
            font-size: 1.1rem;
            margin-right: 10px;
            text-align: center;
        }

        .sidebar__nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 30px;
        }

        .sidebar__nav a.active {
            background-color: #f5f5f5;
            color: #5F7F3B;
            border-left-color: #B79C43; /* Amarillo */
        }
    </style>

    <div class="sidebar__logo">
        <a href="{{ route('dashboard') }}" class="logo-text">
            <i class="fa-solid fa-wheat-awn" style="color: #B79C43;"></i>
            SmartRice
        </a>
    </div>

    <nav class="sidebar__nav">
        <ul>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            
            <li>
                <a href="{{ route('mercado.index') }}" class="{{ request()->routeIs('mercado.*') ? 'active' : '' }}">
                    <i class="fas fa-search-dollar"></i> Mercado de Lotes
                </a>
            </li>
            
            <li>
                <a href="{{ route('campanas.index') }}" class="{{ request()->routeIs('campanas.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i> Mis Campañas
                </a>
            </li>           
            <li>
                <a href="{{ route('molino.logistica.index') }}" class="{{ request()->routeIs('molino.logistica.*') ? 'active' : '' }}">
                    <i class="fas fa-route"></i> Programar Recojos
                </a>
            </li>

            <li>
                <a href="{{ route('molino.pagos.index') }}" class="{{ request()->routeIs('molino.pagos.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> Hacer Pagos
                </a>
            </li>

            <li style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-building"></i> Perfil de Empresa
                    </a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #ffcccc;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</aside>