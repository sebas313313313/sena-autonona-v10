@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col">
            <h2 class="text-primary">
                <i class="fas fa-microchip me-2"></i>Sensores
            </h2>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        @forelse($sensors as $sensor)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-thermometer-half me-2 text-primary"></i>
                            {{ $sensor->nombre }}
                        </h5>
                        <span class="badge {{ $sensor->estado === 'activo' ? 'bg-success' : ($sensor->estado === 'inactivo' ? 'bg-danger' : 'bg-warning') }}">
                            {{ $sensor->estado === 'activo' ? 'Activo' : ($sensor->estado === 'inactivo' ? 'Inactivo' : 'En Mantenimiento') }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Estado del Sensor</label>
                            <select class="form-select sensor-status" 
                                    data-sensor-id="{{ $sensor['id'] }}"
                                    data-current-status="{{ $sensor['estado'] }}">
                                <option value="activo" {{ $sensor['estado'] === 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="inactivo" {{ $sensor['estado'] === 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                                <option value="mantenimiento" {{ $sensor['estado'] === 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Última actualización: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col">
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay sensores registrados para esta granja.
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.badge {
    font-size: 0.9em;
    padding: 0.5em 0.8em;
}
.form-select.sensor-status {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}
.form-select.sensor-status:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios en el estado del sensor
    document.querySelectorAll('.sensor-status').forEach(select => {
        select.addEventListener('change', function() {
            const sensorId = this.dataset.sensorId;
            const newStatus = this.value;
            const currentStatus = this.dataset.currentStatus;

            // Confirmar el cambio de estado
            let statusText = {
                'activo': 'activo',
                'inactivo': 'inactivo',
                'mantenimiento': 'en mantenimiento'
            }[newStatus];
            
            Swal.fire({
                title: '¿Cambiar estado?',
                text: `¿Estás seguro de que quieres cambiar el estado del sensor a ${statusText}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, cambiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Actualizando...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Hacer la petición AJAX
                    fetch(`/sensores/${sensorId}/estado`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            estado: newStatus
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.text().then(text => {
                                console.error('Respuesta del servidor:', text);
                                throw new Error(text);
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: data.message,
                                icon: 'success',
                                timer: 1500
                            }).then(() => {
                                // Recargar la página para ver los cambios
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Error al actualizar el estado');
                        }
                    })
                    .catch(error => {
                        console.error('Error completo:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Error al actualizar el estado del sensor: ' + error.message,
                            icon: 'error'
                        });
                        // Restaurar el valor anterior
                        this.value = currentStatus;
                    });
                } else {
                    // Si el usuario cancela, restaurar el valor anterior
                    this.value = currentStatus;
                }
            });
        });
    });
});
</script>
@endpush
