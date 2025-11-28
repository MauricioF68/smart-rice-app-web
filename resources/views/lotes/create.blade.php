<x-app-layout>
    
    {{-- LEAFLET CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    
    <style>
        .form-input, .form-select { width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 15px; outline: none; transition: all 0.3s ease; font-size: 0.95rem; background-color: #fff; }
        .form-input:focus, .form-select:focus { border-color: #5F7F3B; box-shadow: 0 0 0 3px rgba(95, 127, 59, 0.1); }
        .btn-action { display: inline-flex; align-items: center; justify-content: center; padding: 12px 24px; border-radius: 8px; font-weight: 700; border: none; cursor: pointer; transition: all 0.2s; text-decoration: none; }
        .btn-confirm { background-color: #5F7F3B; color: white; }
        .btn-confirm:hover { background-color: #4b662e; transform: translateY(-1px); }
        .btn-cancel { background-color: white; color: #666; border: 1px solid #ccc; margin-right: 10px; }
        .btn-cancel:hover { background-color: #f3f4f6; color: #333; }
        
        #map { height: 400px; width: 100%; border-radius: 12px; border: 2px solid #e5e7eb; z-index: 1; }

        /* Estilos para la lista de sugerencias (Autocomplete) */
        #suggestions-list {
            position: absolute;
            background: white;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #d1d5db;
            border-top: none;
            border-radius: 0 0 8px 8px;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            display: none; /* Oculto por defecto */
        }
        .suggestion-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            font-size: 0.9rem;
            color: #374151;
        }
        .suggestion-item:hover {
            background-color: #f0fdf4; /* Verde claro al pasar el mouse */
            color: #166534;
        }
    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">
                            <i class="fas fa-map-marked-alt mr-2 text-green-600"></i> Registrar Ubicación de Lote
                        </h2>
                        <p class="text-sm text-gray-500">Geolocaliza tu chacra para facilitar el recojo.</p>
                    </div>
                    <a href="{{ route('lotes.index') }}" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></a>
                </div>

                <div class="p-8">
                    <form method="POST" action="{{ route('lotes.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- COLUMNA IZQUIERDA: DATOS --}}
                            <div class="space-y-6">
                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Nombre o Alias del Lote *</label>
                                    <input type="text" name="nombre_lote" class="form-input" value="{{ old('nombre_lote') }}" required placeholder="Ej. Sector Guadalupe" />
                                    <x-input-error :messages="$errors->get('nombre_lote')" class="mt-2" />
                                </div>

                                <div>
                                    <label class="block font-bold text-sm text-gray-700 mb-2">Variedad de Arroz *</label>
                                    <select name="tipo_arroz_id" class="form-select" required>
                                        <option value="">-- Selecciona --</option>
                                        @foreach ($tiposArroz as $tipo)
                                            <option value="{{ $tipo->id }}" @selected(old('tipo_arroz_id') == $tipo->id)>{{ $tipo->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-bold text-sm text-gray-700 mb-2">Sacos Totales *</label>
                                        <input type="number" name="cantidad_total_sacos" class="form-input" value="{{ old('cantidad_total_sacos') }}" required placeholder="0" />
                                    </div>
                                    
                                    {{-- CAMPO DE BÚSQUEDA CON AUTOCOMPLETE --}}
                                    <div class="relative">
                                        <label class="block font-bold text-sm text-gray-700 mb-2">Buscar Dirección</label>
                                        <div class="relative">
                                            <input type="text" id="address-input" name="referencia_ubicacion" class="form-input pr-10" value="{{ old('referencia_ubicacion') }}" placeholder="Escribe ciudad o zona..." autocomplete="off" />
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-search text-gray-400"></i>
                                            </div>
                                        </div>
                                        {{-- Lista de resultados (Se llena con JS) --}}
                                        <ul id="suggestions-list"></ul>
                                        <p class="text-[10px] text-gray-400 mt-1">Escribe para ver sugerencias.</p>
                                    </div>
                                </div>

                                {{-- Calidad Estimada --}}
                                <div class="bg-green-50 p-4 rounded-lg border border-green-100">
                                    <h4 class="text-xs font-bold text-green-800 uppercase mb-3">Calidad Estimada</h4>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1 font-bold">Humedad (%)</label>
                                            <input type="number" step="0.1" name="humedad" class="form-input text-sm" value="{{ old('humedad') }}" required placeholder="14.5" />
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-600 mb-1 font-bold">Quebrado (%)</label>
                                            <input type="number" step="0.1" name="quebrado" class="form-input text-sm" value="{{ old('quebrado') }}" required placeholder="5.0" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- COLUMNA DERECHA: MAPA --}}
                            <div class="flex flex-col h-full">
                                <label class="block font-bold text-sm text-gray-700 mb-2">
                                    <i class="fas fa-thumbtack text-red-500 mr-1"></i> Ubicación Exacta
                                </label>
                                <p class="text-xs text-gray-500 mb-2">Verifica que el marcador esté en la entrada del lote.</p>
                                
                                <div id="map"></div>

                                {{-- Inputs Ocultos --}}
                                <input type="hidden" name="latitud" id="lat" value="{{ old('latitud') }}">
                                <input type="hidden" name="longitud" id="lng" value="{{ old('longitud') }}">
                                
                                @if($errors->has('latitud'))
                                    <p class="text-red-500 text-xs mt-2 font-bold">⚠️ Por favor, confirma la ubicación en el mapa.</p>
                                @endif
                            </div>

                        </div>

                        <div class="flex items-center justify-end mt-8 pt-6 border-t border-gray-100">
                            <a href="{{ route('lotes.index') }}" class="btn-action btn-cancel">Cancelar</a>
                            <button type="submit" class="btn-action btn-confirm">
                                <i class="fas fa-save mr-2"></i> Registrar Lote
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPTS: LEAFLET SOLO --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Configuración Inicial del Mapa
            // Coordenadas por defecto (Norte del Perú aprox)
            var defaultLat = {{ old('latitud', -7.25) }};
            var defaultLng = {{ old('longitud', -79.50) }};
            
            var map = L.map('map').setView([defaultLat, defaultLng], 13);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);

            var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

            // Funciones de actualización de inputs ocultos
            function updateHiddenInputs(lat, lng) {
                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
            }
            updateHiddenInputs(defaultLat, defaultLng);

            // --- LÓGICA DE AUTOCOMPLETADO (TIPO GOOGLE MAPS) ---
            const addressInput = document.getElementById('address-input');
            const suggestionsList = document.getElementById('suggestions-list');
            let debounceTimer;

            addressInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value;

                if (query.length < 3) {
                    suggestionsList.style.display = 'none';
                    return;
                }

                // Esperar 300ms antes de buscar (para no saturar la API)
                debounceTimer = setTimeout(() => {
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}&countrycodes=pe&limit=5`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsList.innerHTML = ''; // Limpiar lista
                            
                            if (data.length > 0) {
                                suggestionsList.style.display = 'block';
                                data.forEach(item => {
                                    const li = document.createElement('div');
                                    li.className = 'suggestion-item';
                                    // Icono de ubicación + Texto
                                    li.innerHTML = `<i class="fas fa-map-marker-alt text-gray-400 mr-2"></i> ${item.display_name}`;
                                    
                                    // Al hacer clic en una sugerencia
                                    li.addEventListener('click', () => {
                                        // 1. Llenar el input
                                        addressInput.value = item.display_name;
                                        // 2. Ocultar lista
                                        suggestionsList.style.display = 'none';
                                        // 3. Mover mapa
                                        const lat = parseFloat(item.lat);
                                        const lon = parseFloat(item.lon);
                                        map.setView([lat, lon], 16);
                                        marker.setLatLng([lat, lon]);
                                        // 4. Actualizar coordenadas ocultas
                                        updateHiddenInputs(lat, lon);
                                    });
                                    suggestionsList.appendChild(li);
                                });
                            } else {
                                suggestionsList.style.display = 'none';
                            }
                        })
                        .catch(err => console.error('Error buscando:', err));
                }, 300);
            });

            // Ocultar lista si hacen clic fuera
            document.addEventListener('click', function(e) {
                if (e.target !== addressInput && e.target !== suggestionsList) {
                    suggestionsList.style.display = 'none';
                }
            });

            // --- EVENTOS DEL MAPA (MANUAL) ---
            // Si mueven el pin manualmente, actualizamos los inputs
            marker.on('dragend', function(e) {
                var position = marker.getLatLng();
                updateHiddenInputs(position.lat, position.lng);
            });

            // Si hacen clic en el mapa, movemos el pin
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateHiddenInputs(e.latlng.lat, e.latlng.lng);
            });
        });
    </script>
</x-app-layout>