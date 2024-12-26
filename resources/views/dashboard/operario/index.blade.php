@extends('dashboard.layouts.main')

@section('content')
<div class="dashboard-wrapper">
    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datos de Sensores</h5>
                    <div id="sensor-charts">
                        @forelse($sensorData as $sensor)
                            <div class="sensor-chart-container mb-4">
                                <h6>{{ $sensor['nombre'] }}</h6>
                                @if(count($sensor['muestras']) > 0)
                                    <canvas id="sensor-{{ $sensor['id'] }}" width="400" height="200"></canvas>
                                @else
                                    <div class="alert alert-info">
                                        No hay datos disponibles para este sensor aún.
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="alert alert-warning">
                                No hay sensores configurados para esta granja.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Row -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tareas Pendientes</h5>
                    @if($tasks->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tarea</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $task)
                                        <tr>
                                            <td>{{ $task->description }}</td>
                                            <td>{{ $task->date }}</td>
                                            <td>
                                                <span class="badge badge-warning">Pendiente</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            No hay tareas pendientes.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sensorData = @json($sensorData);
    
    sensorData.forEach(sensor => {
        if (sensor.muestras.length > 0) {
            const ctx = document.getElementById('sensor-' + sensor.id).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: sensor.muestras.map(m => m.fecha),
                    datasets: [{
                        label: sensor.nombre,
                        data: sensor.muestras.map(m => m.valor),
                        borderColor: getRandomColor(),
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: 'Últimas 24 horas'
                        }
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Fecha y Hora'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Valor'
                            }
                        }
                    }
                }
            });
        }
    });
});

function getRandomColor() {
    const colors = [
        '#4e73df', // Azul
        '#1cc88a', // Verde
        '#f6c23e', // Amarillo
        '#e74a3b', // Rojo
        '#36b9cc'  // Cyan
    ];
    return colors[Math.floor(Math.random() * colors.length)];
}
</script>
@endpush
@endsection
