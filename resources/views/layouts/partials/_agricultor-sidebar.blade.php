<aside class="sidebar">
    <div class="sidebar__logo">
        <img src="https://via.placeholder.com/150x50.png?text=LogoApp" alt="Logo App">
    </div>
    <nav class="sidebar__nav">
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('preventas.index') }}" class="{{ request()->routeIs('preventas.*') ? 'active' : '' }}"><i class="fas fa-seedling"></i> Mis Preventas</a></li>
            <li><a href="#"><i class="fas fa-map-marked-alt"></i> Mis Hectáreas</a></li>
            <li><a href="#"><i class="fas fa-chart-bar"></i> Historial de Ventas</a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="fas fa-user"></i> Mi Perfil</a></li>
        </ul>
    </nav>
</aside>