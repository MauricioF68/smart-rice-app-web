<x-app-layout>
    
    {{-- CSS LEAFLET --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />

    <style>
        #map { height: 500px; width: 100%; border-radius: 12px; border: 2px solid #e5e7eb; z-index: 1; }
        .info-card { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #e5e7eb; }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i> Detalle de Recojo #{{ $recojo->id }}
                    </h2>
                    <p class="text-gray-500 text-sm">Orden de transporte confirmada.</p>
                </div>
                <a href="{{ route('molino.logistica.index') }}" class="text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- MAPA (SOLO VISUAL) --}}
                <div class="lg:col-span-2">
                    <div id="map"></div>
                </div>

                {{-- INFO --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Estado --}}
                    <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded shadow-sm">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700 font-bold">
                                    Recojo Programado
                                </p>
                                <p class="text-xs text-green-600">
                                    La orden ya fue generada y está en proceso.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3 class="font-bold text-gray-800 border-b pb-2 mb-3">Datos del Transporte</h3>
                        
                        <div class="mb-3">
                            <p class="text-xs text-gray-400 uppercase font-bold">Fecha Programada</p>
                            <p class="text-lg font-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($recojo->fecha_programada)->format('d/m/Y') }}
                            </p>
                        </div>

                        <div class="mb-3">
                            <p class="text-xs text-gray-400 uppercase font-bold">Chofer</p>
                            <p class="text-sm font-bold text-gray-700">{{ $recojo->nombre_chofer }}</p>
                        </div>

                        <div class="mb-3">
                            <p class="text-xs text-gray-400 uppercase font-bold">Placa</p>
                            <span class="bg-yellow-400 text-black px-2 py-1 rounded font-mono font-bold text-sm">
                                {{ $recojo->placa_camion }}
                            </span>
                        </div>

                        <div class="border-t pt-3 mt-3 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400">Distancia</p>
                                <p class="font-bold">{{ $recojo->distancia_km }} km</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Tiempo Est.</p>
                                <p class="font-bold">{{ $recojo->tiempo_estimado }}</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (Solo para pintar la ruta guardada) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var latA = {{ $recojo->preventa->lote->latitud }};
            var lngA = {{ $recojo->preventa->lote->longitud }};
            var latB = {{ Auth::user()->latitud }};
            var lngB = {{ Auth::user()->longitud }};

            var map = L.map('map').setView([latA, lngA], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            L.Routing.control({
                waypoints: [ L.latLng(latA, lngA), L.latLng(latB, lngB) ],
                routeWhileDragging: false,
                draggableWaypoints: false,
                addWaypoints: false,
                showAlternatives: false,
                lineOptions: { styles: [{color: '#059669', opacity: 0.8, weight: 5}] }, // Verde para confirmado
                createMarker: function(i, wp) { return L.marker(wp.latLng); }
            }).addTo(map);
        });
    </script>
</x-app-layout>