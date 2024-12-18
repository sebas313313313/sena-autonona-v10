@extends('layouts.app')

@section('title', 'Granjas - AGROVIDA')

@section('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        body {
            background-color: #f5faff;
            font-family: 'Roboto', sans-serif;
        }

        .header-container {
            background: white;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,.05);
            margin-bottom: 2rem;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-name {
            font-weight: 500;
            color: #2c3e50;
        }

        .btn-logout {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #c82333;
            color: white;
            text-decoration: none;
        }

        .section-container {
            background: #ffffff;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .section-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .farms-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .farm-card {
            background: #ffffff;
            border: 1px solid #e1e8ed;
            border-radius: 12px;
            padding: 1.5rem;
            flex: 1;
            min-width: 300px;
            max-width: 400px;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-bottom: 1rem;
        }

        .farm-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .farm-title {
            color: #2c3e50;
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .farm-type {
            color: #7f8c8d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        .farm-details {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .farm-details li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(0,0,0,.05);
            font-size: 0.95rem;
            color: #4a5568;
        }

        .farm-details li:last-child {
            border-bottom: none;
        }

        .farm-details i {
            color: #3498db;
            width: 20px;
            margin-right: 8px;
        }

        .sensor-count {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .btn-custom {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-1px);
        }

        .btn-view {
            background-color: #3498db;
            color: white;
        }

        .btn-view:hover {
            background-color: #2980b9;
            color: white;
        }

        #map { 
            height: 400px; 
            width: 100%;
            border-radius: 8px;
        }

        .modal-content {
            border-radius: 15px;
        }

        .modal-header {
            background-color: #f8f9fa;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .form-label {
            color: #2c3e50;
            font-weight: 500;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-crear-granja {
            background-color: #198754;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn-crear-granja:hover {
            background-color: #157347;
            color: white;
        }
    </style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Mis Granjas -->
    <div class="section-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title mb-0">
                <i class="fas fa-farm me-2"></i>Mis Granjas
            </h2>
            <button type="button" class="btn btn-crear-granja" data-bs-toggle="modal" data-bs-target="#createFarmModal">
                <i class="fas fa-plus me-2"></i>Crear Granja
            </button>
        </div>

        <div class="farms-container">
            @forelse($farms as $farm)
                <div class="farm-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="farm-content" style="cursor: pointer; width: 100%;" onclick="window.location.href='{{ route('tablero', ['farm_id' => $farm->id]) }}'">
                            <h3 class="farm-title mb-3">{{ $farm->name }}</h3>
                            <ul class="farm-details list-unstyled">
                                <li>
                                    <i class="bi bi-tag me-2"></i>
                                    Tipo: {{ ucfirst($farm->farm_type) }}
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt me-2"></i>
                                    Dirección: {{ $farm->address }}
                                </li>
                                <li>
                                    <i class="bi bi-arrows-angle-expand me-2"></i>
                                    Extensión: {{ $farm->extension }} hectáreas
                                </li>
                                <li>
                                    <i class="bi bi-geo me-2"></i>
                                    Vereda: {{ $farm->vereda }}
                                </li>
                                <li>
                                    <i class="bi bi-pin-map me-2"></i>
                                    Municipio: {{ $farm->municipality->name }}
                                </li>
                            </ul>
                        </div>
                        <form action="{{ route('farms.destroy', $farm) }}" method="POST" class="ms-2" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta granja?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="alert alert-info w-100" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    No tienes granjas creadas aún. ¡Crea tu primera granja!
                </div>
            @endforelse
        </div>
    </div>

    <!-- Granjas Invitadas -->
    <div class="section-container">
        <h2 class="section-title">
            <i class="bi bi-share me-2"></i>
            Granjas Invitadas
        </h2>

        @if(config('app.debug') && isset($debug))
        <div class="alert alert-info">
            <h4>Información de Depuración:</h4>
            <pre>{{ json_encode($debug, JSON_PRETTY_PRINT) }}</pre>
        </div>
        @endif

        <div class="farms-container">
            @forelse($invitedFarms ?? [] as $farm)
                <div class="farm-card" onclick="window.location.href='{{ route('tablero', ['farm_id' => $farm->id]) }}'">
                    <h3 class="farm-title">{{ $farm->address }}</h3>
                    <ul class="farm-details">
                        <li>
                            <i class="bi bi-geo-alt me-2"></i>
                            <span>Municipio: {{ $farm->municipality->name ?? 'Sin municipio' }}</span>
                        </li>
                        <li>
                            <i class="bi bi-arrows-angle-expand me-2"></i>
                            <span>Extensión: {{ $farm->extension }}</span>
                        </li>
                        <li>
                            <i class="bi bi-person me-2"></i>
                            <span>Rol: {{ ucfirst($farm->pivot->role ?? 'Invitado') }}</span>
                        </li>
                        <li>
                            <i class="bi bi-calendar me-2"></i>
                            <span>Invitado el {{ \Carbon\Carbon::parse($farm->pivot->created_at)->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>
            @empty
                <div class="alert alert-info w-100" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    No has sido invitado a ninguna granja.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal para crear granja -->
<div class="modal fade" id="createFarmModal" tabindex="-1" aria-labelledby="createFarmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createFarmModalLabel">Crear Nueva Granja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('farms.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre de la Granja</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" required 
                               value="{{ old('name') }}"
                               placeholder="Ingrese el nombre de la granja">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="farm_type" class="form-label">Tipo de Granja</label>
                        <select class="form-select @error('farm_type') is-invalid @enderror" 
                                id="farm_type" name="farm_type" required>
                            <option value="">Seleccione el tipo de granja</option>
                            <option value="acuaponica" {{ old('farm_type') == 'acuaponica' ? 'selected' : '' }}>Acuapónica</option>
                            <option value="hidroponica" {{ old('farm_type') == 'hidroponica' ? 'selected' : '' }}>Hidropónica</option>
                        </select>
                        @error('farm_type')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="vereda" class="form-label">Vereda</label>
                        <input type="text" class="form-control" id="vereda" name="vereda" required>
                    </div>
                    <div class="mb-3">
                        <label for="extension" class="form-label">Extensión (hectáreas)</label>
                        <input type="number" class="form-control" id="extension" name="extension" required>
                    </div>
                    <div class="mb-3">
                        <label for="municipality_id" class="form-label">Municipio</label>
                        <select class="form-select" id="municipality_id" name="municipality_id" required>
                            <option value="">Seleccione un municipio</option>
                            @foreach($municipalities as $municipality)
                                <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ubicación en el Mapa</label>
                        <div id="map"></div>
                    </div>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitud</label>
                        <input type="number" step="any" class="form-control" id="latitude" name="latitude" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitud</label>
                        <input type="number" step="any" class="form-control" id="longitude" name="longitude" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="sensors" class="form-label">Sensores</label>
                        <select class="form-select" id="sensors" name="sensors[]" multiple size="4">
                            <!-- Los sensores se cargarán dinámicamente según el tipo de granja -->
                        </select>
                        <div class="form-text">Mantenga presionada la tecla Ctrl para seleccionar múltiples sensores</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Granja</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
    <script>
        let map;
        let marker;

        function initMap() {
            // Coordenadas iniciales (Colombia)
            const initialPosition = [4.570868, -74.297333];
            
            // Inicializar el mapa
            map = L.map('map').setView(initialPosition, 5);
            
            // Añadir capa de OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Crear marcador inicial
            marker = L.marker(initialPosition, {
                draggable: true
            }).addTo(map);

            // Actualizar coordenadas cuando se arrastra el marcador
            marker.on('dragend', function(event) {
                const position = marker.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            // Permitir hacer clic en el mapa para mover el marcador
            map.on('click', function(event) {
                const position = event.latlng;
                marker.setLatLng(position);
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Inicializar el modal y el mapa
            const createFarmModal = document.getElementById('createFarmModal');
            createFarmModal.addEventListener('shown.bs.modal', function () {
                setTimeout(() => {
                    map.invalidateSize();
                }, 10);
            });

            initMap();

            // Manejo de sensores según el tipo de granja
            const farmTypeSelect = document.getElementById('farm_type');
            const sensorsSelect = document.getElementById('sensors');

            // Función para cargar los sensores desde el servidor
            async function cargarSensores(tipo) {
                try {
                    console.log('Cargando sensores para tipo:', tipo);
                    const response = await fetch(`/api/sensor/index?type=${tipo}`);
                    const data = await response.json();
                    console.log('Respuesta del servidor:', data);
                    
                    sensorsSelect.innerHTML = '';
                    
                    if (data.data && data.data.length > 0) {
                        console.log('Sensores encontrados:', data.data.length);
                        data.data.forEach(sensor => {
                            const option = new Option(sensor.description, sensor.id);
                            sensorsSelect.add(option);
                        });
                        // Ajustar el tamaño del select según la cantidad de sensores
                        sensorsSelect.size = Math.min(8, data.data.length);
                    } else {
                        console.log('No se encontraron sensores para el tipo:', tipo);
                    }
                } catch (error) {
                    console.error('Error al cargar los sensores:', error);
                }
            }

            farmTypeSelect.addEventListener('change', function() {
                const selectedType = this.value;
                console.log('Tipo de granja seleccionado:', selectedType);
                if (selectedType) {
                    cargarSensores(selectedType);
                } else {
                    sensorsSelect.innerHTML = '';
                }
            });
        });
    </script>
@endsection