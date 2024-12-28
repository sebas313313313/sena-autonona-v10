@extends('dashboard.layouts.main')

@section('content')
<div class="dashboard-wrapper">
    @if(auth()->user()->email === 'admin@gmail.com')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">Debug Logs</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Total de sensores encontrados:</strong> {{ count($sensorData) }}
                        </div>
                        @foreach($debugLogs as $log)
                            <div class="mb-3">
                                <h6 class="text-primary">{{ $log['title'] }}</h6>
                                <pre class="bg-light p-3 rounded" style="max-height: 200px; overflow-y: auto;">{{ json_encode($log['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Charts Row -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Datos de Sensores</h5>
                    <div id="sensor-charts">
                        @php
                            \Log::info('Renderizando vista con sensores:', [
                                'total_sensores' => count($sensorData),
                                'sensores' => collect($sensorData)->map(function($s) {
                                    return [
                                        'id' => $s['id'],
                                        'nombre' => $s['nombre'],
                                        'estado' => $s['estado']
                                    ];
                                })->toArray()
                            ]);
                        @endphp
                        @forelse($sensorData as $sensor)
                            <div class="sensor-chart-container mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6>{{ $sensor['nombre'] }}</h6>
                                    <div class="badge {{ $sensor['estado'] === 'activo' ? 'bg-success' : ($sensor['estado'] === 'inactivo' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $sensor['estado'] === 'activo' ? 'Activo' : ($sensor['estado'] === 'inactivo' ? 'Inactivo' : 'En Mantenimiento') }}
                                    </div>
                                </div>
                                @if($sensor['estado'] === 'inactivo')
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        Este sensor está inactivo
                                    </div>
                                @elseif($sensor['estado'] === 'mantenimiento')
                                    <div class="alert alert-warning">
                                        <i class="fas fa-tools me-2"></i>
                                        Este sensor está en mantenimiento
                                    </div>
                                    @if(count($sensor['muestras']) > 0)
                                        <canvas id="sensor-{{ $sensor['id'] }}" width="400" height="200"></canvas>
                                    @else
                                        <div class="alert alert-info">
                                            No hay datos disponibles para este sensor aún.
                                        </div>
                                    @endif
                                @else
                                    @if(count($sensor['muestras']) > 0)
                                        <canvas id="sensor-{{ $sensor['id'] }}" width="400" height="200"></canvas>
                                    @else
                                        <div class="alert alert-info">
                                            No hay datos disponibles para este sensor aún.
                                        </div>
                                    @endif
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
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
