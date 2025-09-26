<aside class="sidebar">
    <div class="sidebar__logo">
        <img src="https://via.placeholder.com/150x50.png?text=LogoApp" alt="Logo App">
    </div>
    <nav class="sidebar__nav">
        <ul>
            <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="{{ route('mercado.index') }}" class="{{ request()->routeIs('mercado.index') ? 'active' : '' }}"><i class="fas fa-search"></i> Ver Preventas</a></li>
            <li><a href="#"><i class="fas fa-bullhorn"></i> Mis Campañas</a></li>
            <li><a href="#"><i class="fas fa-shopping-cart"></i> Mis Compras</a></li>
            <li><a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="fas fa-building"></i> Perfil de Empresa</a></li>
        </ul>
    </nav>
</aside>