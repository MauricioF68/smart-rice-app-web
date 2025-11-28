<x-app-layout>
    
    {{-- CSS LEAFLET --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

    <style>
        .form-input { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 15px; outline: none; transition: all 0.3s; }
        .form-input:focus { border-color: #5F7F3B; box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }
        #map { height: 450px; width: 100%; border-radius: 12px; border: 2px solid #e5e7eb; z-index: 1; }
        
        /* Autocomplete */
        #suggestions-list { position: absolute; background: white; width: 100%; max-height: 200px; overflow-y: auto; border: 1px solid #d1d5db; border-top: none; z-index: 1000; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); display: none; }
        .suggestion-item { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f3f4f6; font-size: 0.9rem; color: #374151; }
        .suggestion-item:hover { background-color: #f0fdf4; color: #166534; }
    </style>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            {{-- ALERTA INFORMATIVA --}}
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6 rounded-r shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-map-marked-alt text-yellow-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Acción Requerida:</strong> Para calcular las rutas de recojo y distancias, necesitamos saber la ubicación exacta de tu Molino/Planta.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-xl rounded-xl overflow-hidden border border-gray-100">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                        <i class="fas fa-industry text-green-600 mr-2"></i> Configurar Ubicación del Molino
                    </h2>

                    <form method="POST" action="{{ route('molino.logistica.guardar') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            
                            {{-- COLUMNA DATOS --}}
                            <div class="md:col-span-1 space-y-4">
                                <div class="relative">
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Buscar Dirección</label>
                                    <div class="relative">
                                        <input type="text" id="address-input" name="direccion_referencia" class="form-input pr-10" placeholder="Ej: Panamericana Norte Km..." autocomplete="off">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-search text-gray-400"></i>
                                        </div>
                                    </div>
                                    <div id="suggestions-list"></div>
                                    <p class="text-[10px] text-gray-400 mt-1">Usa el buscador o mueve el pin manualmente.</p>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full py-3 bg-[#5F7F3B] hover:bg-[#4b662e] text-white font-bold rounded-lg shadow transition transform hover:-translate-y-0.5">
                                        <i class="fas fa-save mr-2"></i> Guardar Ubicación
                                    </button>
                                </div>
                            </div>

                            {{-- COLUMNA MAPA --}}
                            <div class="md:col-span-2">
                                <label class="block font-bold text-sm text-gray-700 mb-2">Mapa</label>
                                <div id="map"></div>
                                
                                {{-- Inputs Ocultos --}}
                                <input type="hidden" name="latitud" id="lat">
                                <input type="hidden" name="longitud" id="lng">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS (MISMA LÓGICA QUE EL AGRICULTOR) --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var defaultLat = -7.25; 
            var defaultLng = -79.50;
            
            var map = L.map('map').setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
            var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            function updateInputs(lat, lng) {
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            }
            updateInputs(defaultLat, defaultLng);

            // --- AUTOCOMPLETE ---
            const addressInput = document.getElementById('address-input');
            const suggestionsList = document.getElementById('suggestions-list');
            let debounceTimer;

            addressInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;
                if (query.length < 3) { suggestionsList.style.display = 'none'; return; }

                debounceTimer = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=pe&limit=5`)
                        .then(res => res.json())
                        .then(data => {
                            suggestionsList.innerHTML = '';
                            if (data.length > 0) {
                                suggestionsList.style.display = 'block';
                                data.forEach(item => {
                                    const div = document.createElement('div');
                                    div.className = 'suggestion-item';
                                    div.innerText = item.display_name;
                                    div.addEventListener('click', () => {
                                        addressInput.value = item.display_name;
                                        suggestionsList.style.display = 'none';
                                        const lat = parseFloat(item.lat);
                                        const lon = parseFloat(item.lon);
                                        map.setView([lat, lon], 16);
                                        marker.setLatLng([lat, lon]);
                                        updateInputs(lat, lon);
                                    });
                                    suggestionsList.appendChild(div);
                                });
                            } else { suggestionsList.style.display = 'none'; }
                        });
                }, 300);
            });

            // Eventos Mapa
            marker.on('dragend', function(e) {
                var pos = marker.getLatLng();
                updateInputs(pos.lat, pos.lng);
            });
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateInputs(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
</x-app-layout>