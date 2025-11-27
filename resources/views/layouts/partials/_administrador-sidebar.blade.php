<aside class="sidebar">
    
    {{-- ESTILOS: Copia exacta del diseño de Agricultor/Molino --}}
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

        /* Efecto Hover */
        .sidebar__nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            padding-left: 30px;
        }

        /* Estado Activo */
        .sidebar__nav a.active {
            background-color: #f5f5f5;
            color: #5F7F3B;
            border-left-color: #B79C43; /* Amarillo Acento */
        }
    </style>

    {{-- LOGO --}}
    <div class="sidebar__logo">
        <a href="{{ route('dashboard') }}" class="logo-text">
            <i class="fa-solid fa-wheat-awn" style="color: #B79C43;"></i>
            SmartRice
            {{-- Badge pequeño para indicar que es Admin --}}
            <span style="font-size: 0.6rem; background: #B79C43; color: white; padding: 2px 6px; border-radius: 4px; margin-left: 8px; vertical-align: middle; letter-spacing: 1px;">ADMIN</span>
        </a>
    </div>

    {{-- MENÚ DE NAVEGACIÓN --}}
    <nav class="sidebar__nav">
        <ul>
            {{-- 1. Dashboard (Ruta General) --}}
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </li>
            
            {{-- 2. Gestión de Tipos de Arroz (Ruta Específica de Admin) --}}
            <li>
                {{-- Usamos admin.tipos-arroz.* para que se mantenga activo en crear/editar --}}
                <a href="{{ route('admin.tipos-arroz.index') }}" class="{{ request()->routeIs('admin.tipos-arroz.*') ? 'active' : '' }}">
                    <i class="fas fa-seedling"></i> Tipos de Arroz
                </a>
            </li>
            {{-- GESTIÓN DE CASETAS --}}
            <li>
                <a href="{{ route('admin.casetas.index') }}" class="{{ request()->routeIs('admin.casetas.*') ? 'active' : '' }}">
                    <i class="fas fa-warehouse"></i> Gestión de Casetas
                </a>
            </li>
            {{-- GESTIÓN DE USUARIOS CASETA --}}
            <li>
            <a href="{{ route('admin.usuarios-caseta.index') }}" class="{{ request()->routeIs('admin.usuarios-caseta.*') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i> Operarios Caseta
            </a>
        </li>

            {{-- Espaciador para separar gestión de cuenta --}}
            <li style="margin-top: 2rem;"></li>

            {{-- 3. Perfil (Ruta General) --}}
            <li>
                <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Perfil Administrador
                </a>
            </li>

            {{-- 4. Cerrar Sesión --}}
            <li style="border-top: 1px solid rgba(255,255,255,0.1);">
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