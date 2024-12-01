@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Widgets</h2>
    
    <div class="row">
        <!-- Tarjeta de Estadísticas -->
        <div class="col-md-3 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Usuarios Totales</h5>
                    <h2 class="display-4">1,289</h2>
                    <p class="mb-0"><i class="fas fa-arrow-up"></i> 12% más que el mes pasado</p>
                </div>
            </div>
        </div>

        <!-- Widget de Clima -->
        <div class="col-md-3 mb-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Clima Actual</h5>
                    <div class="d-flex align-items-center">
                        <i class="fas fa-sun fa-3x me-3"></i>
                        <div>
                            <h2 class="mb-0">24°C</h2>
                            <p>Soleado</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget de Tareas -->
        <div class="col-md-3 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Tareas Completadas</h5>
                    <div class="progress mb-3" style="height: 20px;">
                        <div class="progress-bar" role="progressbar" style="width: 75%;" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100">75%</div>
                    </div>
                    <p class="mb-0">18 de 24 tareas completadas</p>
                </div>
            </div>
        </div>

        <!-- Widget de Notificaciones -->
        <div class="col-md-3 mb-4">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <h5 class="card-title">Notificaciones</h5>
                    <h2 class="display-4">7</h2>
                    <p class="mb-0">3 mensajes nuevos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda fila de widgets -->
    <div class="row">
        <!-- Widget de Calendario -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Calendario de Eventos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Evento</th>
                                    <th>Fecha</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reunión de Equipo</td>
                                    <td>10 Nov 2023</td>
                                    <td><span class="badge bg-success">Confirmado</span></td>
                                </tr>
                                <tr>
                                    <td>Presentación de Proyecto</td>
                                    <td>15 Nov 2023</td>
                                    <td><span class="badge bg-warning">Pendiente</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Widget de Actividad Reciente -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actividad Reciente</h5>
                </div>
                <div class="card-body">
                    <div class="activity-feed">
                        <div class="feed-item pb-3">
                            <div class="d-flex">
                                <div class="feed-icon bg-primary text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>Juan Pérez</strong> actualizó su perfil</p>
                                    <small class="text-muted">Hace 5 minutos</small>
                                </div>
                            </div>
                        </div>
                        <div class="feed-item pb-3">
                            <div class="d-flex">
                                <div class="feed-icon bg-success text-white rounded-circle p-2 me-3">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div>
                                    <p class="mb-0"><strong>María García</strong> completó una tarea</p>
                                    <small class="text-muted">Hace 10 minutos</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feed-icon {
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.activity-feed {
    max-height: 300px;
    overflow-y: auto;
}
</style>
@endsection
