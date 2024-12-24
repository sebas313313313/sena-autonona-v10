@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <!-- Formulario de Asignación de Tareas -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Asignar Nueva Tarea</h5>
        </div>
        <div class="card-body">
            @php
                $operarios = $farm->users()->wherePivot('role', 'operario')->get();
            @endphp

            @if($operarios->isEmpty())
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    No hay operarios disponibles en esta granja. Invita operarios desde la sección de usuarios para poder asignar tareas.
                </div>
            @else
                <form action="{{ route('tasks.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="farm_id" value="{{ $farm->id }}">
                    
                    <div class="row">
                        <!-- Select de Operario -->
                        <div class="col-md-4 mb-3">
                            <label for="user_id" class="form-label">Asignar a Operario:</label>
                            <select class="form-select" name="user_id" id="user_id" required>
                                <option value="">Seleccionar operario...</option>
                                @foreach($operarios as $operario)
                                    <option value="{{ $operario->id }}">{{ $operario->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Fecha y Hora -->
                        <div class="col-md-4 mb-3">
                            <label for="date" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="time" class="form-label">Hora:</label>
                            <input type="time" class="form-control" id="time" name="time" required>
                        </div>
                    </div>

                    <!-- Estado de la Tarea -->
                    <div class="mb-3">
                        <label class="form-label d-block">Estado:</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="taskStatus" name="status" value="active" checked>
                            <label class="form-check-label" for="taskStatus">Activa</label>
                        </div>
                    </div>

                    <!-- Comentarios -->
                    <div class="mb-3">
                        <label for="comments" class="form-label">Descripción de la Tarea:</label>
                        <textarea class="form-control" id="comments" name="comments" rows="3" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Asignar Tarea
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Lista de Tareas -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Tareas Asignadas</h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @forelse($farm->tasks as $task)
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $task->user->name }}</h6>
                                <p class="mb-1">{{ $task->comments }}</p>
                                <small class="text-muted">
                                    Fecha: {{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }} 
                                    Hora: {{ \Carbon\Carbon::parse($task->time)->format('H:i') }}
                                </small>
                            </div>
                            <div class="d-flex align-items-center">
                                <!-- Estado Activo/Inactivo -->
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="me-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm {{ $task->status === 'active' ? 'btn-success' : 'btn-danger' }}"
                                            title="{{ $task->status === 'active' ? 'Activa' : 'Inactiva' }}">
                                        <i class="fas {{ $task->status === 'active' ? 'fa-check' : 'fa-times' }}"></i>
                                    </button>
                                </form>
                                
                                <!-- Estado Completado -->
                                <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="btn btn-sm {{ $task->status === 'completed' ? 'btn-info' : 'btn-warning' }}"
                                            title="{{ $task->status === 'completed' ? 'Completada' : 'Pendiente' }}">
                                        <i class="fas {{ $task->status === 'completed' ? 'fa-check-double' : 'fa-clock' }}"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-3">
                        <p class="text-muted mb-0">No hay tareas asignadas</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inicialización de componentes
    $(document).ready(function() {
        // Establecer fecha actual por defecto
        const today = new Date().toISOString().split('T')[0];
        $('#date').val(today);
        
        // Establecer hora actual por defecto
        const now = new Date();
        const currentTime = String(now.getHours()).padStart(2, '0') + ':' + 
                          String(now.getMinutes()).padStart(2, '0');
        $('#time').val(currentTime);
    });
</script>
@endpush
@endsection
