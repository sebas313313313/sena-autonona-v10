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
        font-weight: 500;
        transition: background-color 0.3s ease;
    }
    .btn-crear-granja:hover {
        background-color: #146c43;
        color: white;
    }

    /* Estilos para el modal */
    .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .modal-header {
        border-bottom: 1px solid #f0f0f0;
        background-color: #f8f9fa;
        border-radius: 15px 15px 0 0;
        padding: 1.5rem;
    }
    
    .modal-body {
        padding: 1.5rem;
    }
    
    .modal-footer {
        border-top: 1px solid #f0f0f0;
        border-radius: 0 0 15px 15px;
        padding: 1.2rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #2c3e50;
    }
    
    .form-control {
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        padding: 0.6rem 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.15);
    }
    
    .form-group label {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }
    
    .close {
        font-size: 1.5rem;
        opacity: 0.7;
        transition: opacity 0.3s ease;
    }
    
    .close:hover {
        opacity: 1;
    }
    
    .modal .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .modal .btn-primary {
        background-color: #198754;
        border: none;
    }
    
    .modal .btn-primary:hover {
        background-color: #146c43;
    }
    
    .modal .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
    
    .modal .btn-secondary:hover {
        background-color: #5a6268;
    }

    /* Estilos para las alertas */
    .alert {
        border: none;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,.08);
        margin-bottom: 1.5rem;
    }
    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #842029;
    }
    .alert .btn-close {
        padding: 1.05rem 1rem;
        opacity: .75;
    }
    .alert .btn-close:hover {
        opacity: 1;
    }
    .alert i {
        font-size: 1.1rem;
    }
    .alert-heading {
        color: inherit;
        font-size: 1.1rem;
        font-weight: 600;
    }
    .alert ul li {
        margin-bottom: 0.25rem;
    }
    .alert ul li:last-child {
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="container py-4">
    <!-- Mensajes de éxito y error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill flex-shrink-0 me-2"></i>
            <div>
                {{ session('success') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
            <div>
                {{ session('error') }}
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                <div>
                    <h5 class="alert-heading mb-1">Por favor, corrige los siguientes errores:</h5>
                    <ul class="list-unstyled mb-0">
                        @foreach($errors->all() as $error)
                            <li><i class="bi bi-dot me-2"></i>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header Section -->
    
            <div class="header-left">
                <h1 class="display-5 fw-bold mb-0">Granjas</h1>
                <p class="fs-5 mb-0">Aquí están registradas todas las granjas que tienes hasta el momento.</p>
            </div>
           
        

    <!-- Create Button Section -->
    <div class="container">
        <div class="row">
            <div class="col-12 text-end mb-4">
                <button type="button" class="btn btn-crear-granja" data-bs-toggle="modal" data-bs-target="#createFarmModal">
                    <i class="bi bi-plus-lg me-2"></i>
                    Crear Granja
                </button>
            </div>
        </div>
    </div>

    <!-- Farms Grid -->
    <div class="farms-container">
        @if($farms->count() > 0)
            @foreach($farms as $farm)
                <div class="farm-card" onclick="window.location.href='{{ route('dashboard', ['farm_id' => $farm->id]) }}'">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="farm-content" style="width: 100%;">
                            <h3 class="farm-title">{{ $farm->address }}</h3>
                            <ul class="farm-details">
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
                            </ul>
                        </div>
                        <button class="btn btn-link text-danger delete-farm" style="font-size: 1.2rem;" onclick="event.stopPropagation(); confirmDelete({{ $farm->id }}, '{{ $farm->address }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="no-farms-message">
                <i class="bi bi-inbox text-muted mb-3" style="font-size: 3rem;"></i>
                <h3>No hay granjas registradas</h3>
                <p class="text-muted">Usa el botón "Crear Granja" para registrar tu primera granja.</p>
            </div>
        @endif
    </div>

    <!-- Modal para crear granja -->
    <div class="modal fade" id="createFarmModal" tabindex="-1" aria-labelledby="createFarmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFarmModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Crear Nueva Granja
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createFarmForm" action="{{ route('farms.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="address" class="form-label">
                                <i class="bi bi-house me-2"></i>
                                Nombre de la Granja
                            </label>
                            <input type="text" class="form-control" id="address" name="address" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="vereda" class="form-label">
                                <i class="bi bi-geo me-2"></i>
                                Vereda
                            </label>
                            <input type="text" class="form-control" id="vereda" name="vereda" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="extension" class="form-label">
                                <i class="bi bi-arrows-angle-expand me-2"></i>
                                Extensión
                            </label>
                            <input type="number" class="form-control" id="extension" name="extension" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="municipality_id" class="form-label">
                                <i class="bi bi-building me-2"></i>
                                Municipio
                            </label>
                            <select class="form-select" id="municipality_id" name="municipality_id" required>
                                <option value="">Seleccione un municipio</option>
                                @foreach($municipalities as $municipality)
                                    <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="latitude" class="form-label">
                                <i class="bi bi-geo-alt me-2"></i>
                                Latitud
                            </label>
                            <input type="number" step="any" class="form-control" id="latitude" name="latitude" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="longitude" class="form-label">
                                <i class="bi bi-geo-alt me-2"></i>
                                Longitud
                            </label>
                            <input type="number" step="any" class="form-control" id="longitude" name="longitude" required>
                        </div>
                        <div class="modal-footer px-0 pb-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check2 me-2"></i>
                                Crear Granja
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="deleteFarmModal" tabindex="-1" aria-labelledby="deleteFarmModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteFarmModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                        Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar la granja "<span id="farmToDelete"></span>"?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <form id="deleteFarmForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash me-2"></i>
                            Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmDelete(farmId, farmAddress) {
        document.getElementById('farmToDelete').textContent = farmAddress;
        document.getElementById('deleteFarmForm').action = `/farms/${farmId}`;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteFarmModal'));
        deleteModal.show();
    }
</script>
@endsection