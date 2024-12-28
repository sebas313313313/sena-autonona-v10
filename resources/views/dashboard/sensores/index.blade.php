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
                        <span class="badge {{ $sensor->estado ? 'bg-success' : 'bg-danger' }}">
                            {{ $sensor->estado ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Estado del Sensor</label>
                            <select class="form-select sensor-status" 
                                    data-sensor-id="{{ $sensor->id }}"
                                    data-current-status="{{ $sensor->estado }}">
                                <option value="1" {{ $sensor->estado == 1 ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ $sensor->estado == 0 ? 'selected' : '' }}>Inactivo</option>
                                <option value="2" {{ $sensor->estado == 2 ? 'selected' : '' }}>En Mantenimiento</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Última actualización: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
                            </small>
                            <button class="btn btn-sm btn-outline-primary view-details">
                                <i class="fas fa-chart-line me-1"></i>Ver Detalles
                            </button>
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

<!-- Modal de Detalles -->
<div class="modal fade" id="sensorDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-chart-line me-2"></i>
                    Detalles del Sensor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="sensor-chart-container">
                    <canvas id="sensorChart"></canvas>
                </div>
            </div>
        </div>
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
.sensor-chart-container {
    height: 300px;
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Manejar cambios en el estado del sensor
    document.querySelectorAll('.sensor-status').forEach(select => {
        select.addEventListener('change', function() {
            const sensorId = this.dataset.sensorId;
            const newStatus = this.value;

            fetch(`/sensores/${sensorId}/estado`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ estado: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar la badge del estado
                    const card = select.closest('.card');
                    const badge = card.querySelector('.badge');
                    badge.className = `badge ${newStatus == 1 ? 'bg-success' : 'bg-danger'}`;
                    badge.textContent = newStatus == 1 ? 'Activo' : 'Inactivo';
                    
                    // Mostrar mensaje de éxito
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        Estado actualizado correctamente
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.row'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Mostrar mensaje de error
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Error al actualizar el estado
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.row'));
            });
        });
    });

    // Manejar clic en el botón de detalles
    document.querySelectorAll('.view-details').forEach(button => {
        button.addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('sensorDetailsModal'));
            modal.show();

            // Crear gráfico de ejemplo
            const ctx = document.getElementById('sensorChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                    datasets: [{
                        label: 'Lecturas del Sensor',
                        data: [12, 19, 3, 5, 2, 3],
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        });
    });
});
</script>
@endpush
