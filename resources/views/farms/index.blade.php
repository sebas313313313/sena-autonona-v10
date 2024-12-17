@extends('layouts.app')

@section('title', 'Granjas - AGROVIDA')

@section('styles')
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
    .farms-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        padding: 20px 0;
    }
    .farm-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,.05);
        transition: all 0.3s ease;
        cursor: pointer;
        border: 1px solid rgba(0,0,0,.05);
        width: 100%;
        max-width: 350px;
        padding: 20px;
    }
    .farm-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,.1);
    }
    .farm-title {
        text-align: center;
        font-weight: 600;
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: #2c3e50;
        padding: 0.5rem 0;
        border-bottom: 2px solid #f8f9fa;
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
    }
    .farm-details li:last-child {
        border-bottom: none;
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
    .section-title {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e9ecef;
    }
    .section-container {
        margin-bottom: 3rem;
    }
    .farm-card {
        cursor: pointer;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .farm-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .farm-title {
        color: #2c3e50;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #eee;
    }
    #map {
        height: 400px;
        width: 100%;
        margin-bottom: 20px;
        border-radius: 8px;
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
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="farm-content" style="cursor: pointer; width: 100%;" onclick="window.location.href='{{ route('tablero', ['farm_id' => $farm->id]) }}'">
                            <h3 class="farm-title">{{ $farm->address }}</h3>
                            <ul class="farm-details list-unstyled">
                                <li>
                                    <i class="bi bi-arrows-angle-expand me-2"></i>
                                    Extensión: {{ $farm->extension }}
                                </li>
                                <li>
                                    <i class="bi bi-geo-alt me-2"></i>
                                    Vereda: {{ $farm->vereda }}
                                </li>
                                <li>
                                    <i class="bi bi-geo me-2"></i>
                                    Coordenadas: {{ number_format($farm->latitude, 6) }}, {{ number_format($farm->longitude, 6) }}
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
                        <label for="name" class="form-label">Direccion</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Nombre de la Granja</label>
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
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&callback=initMap" async defer></script>
<script>
    let map;
    let marker;

    function initMap() {
        // Coordenadas iniciales (Colombia)
        const initialPosition = { lat: 4.570868, lng: -74.297333 };
        
        map = new google.maps.Map(document.getElementById('map'), {
            zoom: 5,
            center: initialPosition,
        });

        // Crear marcador inicial
        marker = new google.maps.Marker({
            position: initialPosition,
            map: map,
            draggable: true
        });

        // Actualizar coordenadas cuando se arrastra el marcador
        google.maps.event.addListener(marker, 'dragend', function(event) {
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });

        // Permitir hacer clic en el mapa para mover el marcador
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            document.getElementById('latitude').value = event.latLng.lat();
            document.getElementById('longitude').value = event.latLng.lng();
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar el modal
        const createFarmModal = document.getElementById('createFarmModal');
        createFarmModal.addEventListener('shown.bs.modal', function () {
            // Recargar el mapa cuando se muestra el modal
            google.maps.event.trigger(map, 'resize');
        });
    });
</script>
@endsection