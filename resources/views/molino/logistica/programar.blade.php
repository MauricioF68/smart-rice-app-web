<x-app-layout>
    
    {{-- CSS LEAFLET + ROUTING MACHINE --}}
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
                        <i class="fas fa-route text-green-600 mr-2"></i> Ruta de Recojo
                    </h2>
                    <p class="text-gray-500 text-sm">Planifica el viaje desde el lote hasta el molino.</p>
                </div>
                <a href="{{ route('molino.logistica.index') }}" class="text-gray-500 hover:text-gray-700 font-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- COLUMNA MAPA --}}
                <div class="lg:col-span-2">
                    <div id="map"></div>
                </div>

                {{-- COLUMNA DETALLES --}}
                <div class="lg:col-span-1 space-y-6">
                    
                    {{-- Tarjeta de Ruta --}}
                    <div class="info-card">
                        <h3 class="font-bold text-gray-800 border-b pb-2 mb-3">Detalles del Viaje</h3>
                        
                        <div class="flex items-start mb-4">
                            <div class="text-green-600 mt-1 mr-3"><i class="fas fa-map-marker-alt"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">Origen (Lote)</p>
                                <p class="text-sm font-bold text-gray-700">{{ $preventa->lote->nombre_lote }}</p>
                                <p class="text-xs text-gray-500">{{ $preventa->lote->referencia_ubicacion }}</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="text-blue-600 mt-1 mr-3"><i class="fas fa-industry"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold">Destino (Molino)</p>
                                <p class="text-sm font-bold text-gray-700">{{ Auth::user()->nombre_comercial ?? Auth::user()->name }}</p>
                            </div>
                        </div>
                        
                        {{-- Resumen de Ruta (Se llena con JS) --}}
                        <div id="resumen-ruta" class="mt-4 pt-3 border-t border-dashed border-gray-300 hidden">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Distancia:</span>
                                <span class="font-bold text-gray-800" id="txt-distancia">---</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Tiempo estimado:</span>
                                <span class="font-bold text-gray-800" id="txt-tiempo">---</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tarjeta de Asignación --}}
                    <div class="info-card bg-green-50 border-green-100">
                        <h3 class="font-bold text-green-800 mb-3">Asignar Recojo</h3>
                        
                        {{-- FORMULARIO CONECTADO --}}
                        <form action="{{ route('molino.logistica.store', $preventa->id) }}" method="POST"> 
                            @csrf
                            
                            {{-- Inputs Ocultos (Datos del Mapa) --}}
                            <input type="hidden" name="distancia_km" id="input_distancia">
                            <input type="hidden" name="tiempo_estimado" id="input_tiempo">

                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Fecha Programada</label>
                                <input type="date" name="fecha_programada" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500" required min="{{ date('Y-m-d') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Placa del Camión</label>
                                <input type="text" name="placa_camion" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500 uppercase" placeholder="Ej: ABC-123" required>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-xs font-bold text-gray-600 mb-1">Nombre del Chofer</label>
                                <input type="text" name="nombre_chofer" class="w-full rounded border-gray-300 text-sm focus:border-green-500 focus:ring-green-500" placeholder="Ej: Juan Perez" required>
                            </div>
                            
                            <button type="submit" class="w-full py-2 bg-[#5F7F3B] hover:bg-[#4b662e] text-white font-bold rounded shadow transition flex justify-center items-center">
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Recojo
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // Coordenadas
            var latA = {{ $preventa->lote->latitud }};
            var lngA = {{ $preventa->lote->longitud }};
            
            var latB = {{ Auth::user()->latitud }};
            var lngB = {{ Auth::user()->longitud }};

            var map = L.map('map').setView([latA, lngA], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            // Trazar Ruta
            var control = L.Routing.control({
                waypoints: [
                    L.latLng(latA, lngA),
                    L.latLng(latB, lngB)
                ],
                routeWhileDragging: false,
                draggableWaypoints: false,
                addWaypoints: false,
                showAlternatives: false,
                lineOptions: { styles: [{color: '#3b82f6', opacity: 0.7, weight: 5}] },
                createMarker: function(i, wp, nWps) {
                    var color = (i === 0) ? 'green' : 'blue'; 
                    return L.marker(wp.latLng);
                }
            }).addTo(map);

            // --- ESCUCHAR RESULTADOS DE LA RUTA ---
            control.on('routesfound', function(e) {
                var routes = e.routes;
                var summary = routes[0].summary;
                
                // Conversión de datos
                var km = (summary.totalDistance / 1000).toFixed(2);
                var tiempoSegundos = summary.totalTime;
                var horas = Math.floor(tiempoSegundos / 3600);
                var minutos = Math.floor((tiempoSegundos % 3600) / 60);
                var textoTiempo = "";
                if(horas > 0) textoTiempo += horas + " h ";
                textoTiempo += minutos + " min";

                // 1. Poner datos en los inputs ocultos
                document.getElementById('input_distancia').value = km;
                document.getElementById('input_tiempo').value = textoTiempo;

                // 2. Mostrar resumen visualmente
                document.getElementById('txt-distancia').innerText = km + ' km';
                document.getElementById('txt-tiempo').innerText = textoTiempo;
                document.getElementById('resumen-ruta').classList.remove('hidden');
            });
        });
    </script>
</x-app-layout>