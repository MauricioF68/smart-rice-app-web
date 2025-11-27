<aside class="sidebar">
    <style>
        /* Reutilizamos los estilos base de SmartRice */
        .sidebar { width: 260px; min-height: 100vh; background-color: #5F7F3B; color: white; display: flex; flex-direction: column; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar__logo { padding: 1.5rem; text-align: center; background-color: rgba(0, 0, 0, 0.05); margin-bottom: 1rem; }
        .logo-text { font-size: 1.5rem; font-weight: 800; color: white; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .sidebar__nav ul { list-style: none; padding: 0; margin: 0; }
        .sidebar__nav li { width: 100%; }
        .sidebar__nav a { display: flex; align-items: center; padding: 15px 25px; color: rgba(255, 255, 255, 0.8); text-decoration: none; font-size: 0.95rem; font-weight: 600; transition: all 0.3s ease; border-left: 5px solid transparent; }
        .sidebar__nav a i { width: 25px; font-size: 1.1rem; margin-right: 10px; text-align: center; }
        .sidebar__nav a:hover { background-color: rgba(255, 255, 255, 0.1); color: white; padding-left: 30px; }
        .sidebar__nav a.active { background-color: #f5f5f5; color: #5F7F3B; border-left-color: #B79C43; }
        
        /* Badge especial para indicar la caseta actual */
        .caseta-badge { font-size: 0.7rem; background: #B79C43; color: white; padding: 4px 8px; border-radius: 4px; display: block; margin-top: 5px; opacity: 0.9; }
    </style>

    <div class="sidebar__logo">
        <a href="{{ route('caseta.dashboard') }}" class="logo-text" style="flex-direction: column; gap: 0;">
            <span><i class="fa-solid fa-wheat-awn" style="color: #B79C43;"></i> SmartRice</span>
            
            {{-- Mostramos dónde está trabajando --}}
            @if(session('caseta_activa'))
                <span class="caseta-badge">
                    <i class="fas fa-map-marker-alt mr-1"></i> {{ session('caseta_activa')->nombre }}
                </span>
            @endif
        </a>
    </div>

    <nav class="sidebar__nav">
        <ul>
            {{-- 1. DASHBOARD (Métricas) --}}
            <li>
                <a href="{{ route('caseta.dashboard') }}" class="{{ request()->routeIs('caseta.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            
            {{-- 2. RECEPCIÓN (Buscador DNI) --}}
            <li>
                <a href="{{ route('caseta.recepcion') }}" class="{{ request()->routeIs('caseta.recepcion') ? 'active' : '' }}">
                    <i class="fas fa-truck-loading"></i> Recepción y Pesaje
                </a>
            </li>

            <li style="margin-top: 2rem; border-top: 1px solid rgba(255,255,255,0.1);">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" style="color: #ffcccc;">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Turno
                    </a>
                </form>
            </li>
        </ul>
    </nav>
</aside>