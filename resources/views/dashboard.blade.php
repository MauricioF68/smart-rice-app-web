<x-app-layout>
    
    <style>
        .dash-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .dash-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
            border-left: 5px solid #ccc; /* Color por defecto */
        }
        
        .dash-card:hover {
            transform: translateY(-5px);
        }

        .card-info h3 { color: #888; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .card-info p { color: #333; font-size: 2rem; font-weight: 800; margin-top: 5px; }

        .card-icon {
            width: 50px; height: 50px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            opacity: 0.2;
        }

        /* Variantes de Tarjetas */
        .card-green { border-left-color: var(--color-primary); }
        .card-green .card-icon { background: var(--color-primary); color: var(--color-primary); opacity: 1; color: white; }

        .card-yellow { border-left-color: var(--color-secondary); }
        .card-yellow .card-icon { background: var(--color-secondary); color: var(--color-secondary); opacity: 1; color: white; }

        .card-dark { border-left-color: #333; }
        .card-dark .card-icon { background: #333; color: #333; opacity: 1; color: white; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="dash-header">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        Hola, <span style="color: var(--color-primary);">{{ Auth::user()->primer_nombre ?? Auth::user()->name }}</span> 👋
                    </h1>
                    <p class="text-gray-500 mt-1">Aquí tienes el resumen de tu actividad en SmartRice.</p>
                </div>

                <div>
                    @if(auth()->user()->rol === 'agricultor')
                        <a href="{{ route('preventas.create') }}" class="btn-action btn-confirm">
                            <i class="fas fa-plus"></i> Nueva Preventa
                        </a>
                    @elseif(auth()->user()->rol === 'molino')
                        <a href="{{ route('campanas.create') }}" class="btn-action btn-edit">
                            <i class="fas fa-bullhorn"></i> Nueva Campaña
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Tarjeta 1: Preventas (Verde) --}}
                <div class="dash-card card-green">
                    <div class="card-info">
                        <h3>Preventas Activas</h3>
                        <p>{{ $preventasActivas ?? 0 }}</p> {{-- El '?? 0' evita error si la variable no existe --}}
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>
                
                {{-- Tarjeta 2: Propuestas (Amarillo) --}}
                <div class="dash-card card-yellow">
                    <div class="card-info">
                        <h3>Propuestas Recibidas</h3>
                        <p>{{ $propuestasRecibidas ?? 0 }}</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
                
                {{-- Tarjeta 3: Ventas (Oscuro) --}}
                <div class="dash-card card-dark">
                    <div class="card-info">
                        <h3>Ventas Completadas</h3>
                        <p>{{ $ventasCompletadas ?? 0 }}</p>
                    </div>
                    <div class="card-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                </div>

            </div>
            
            @if( ($preventasActivas ?? 0) == 0 && ($propuestasRecibidas ?? 0) == 0 )
                <div class="mt-8 p-6 bg-white rounded-lg border border-dashed border-gray-300 text-center">
                    <div style="font-size: 3rem; color: #ddd; margin-bottom: 1rem;">
                        <i class="fa-solid fa-seedling"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">Aún no tienes actividad reciente</h3>
                    <p class="text-gray-500 mb-4">¡Empieza creando tu primera operación para ver las estadísticas aquí!</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>