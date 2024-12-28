@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    {{-- Formulario de Asignación de Tareas (Solo visible para administradores) --}}
    @if(!$isOperario)
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
                    <form action="{{ route('tasks.store', ['farm_id' => $farm->id]) }}" method="POST">
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
                                <input class="form-check-input" type="checkbox" id="taskStatus" name="status" value="1" checked>
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
    @endif

    <!-- Lista de Tareas -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                @if($isOperario)
                    Mis Tareas Asignadas
                @else
                    Todas las Tareas
                @endif
            </h5>
        </div>
        <div class="card-body">
            <div class="list-group">
                @if($isOperario)
                    @forelse($tasks as $task)
                        <div class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">Tarea #{{ $task->id }}</h6>
                                    <p class="mb-1">{{ $task->comments }}</p>
                                    <small class="text-muted">
                                        Fecha: {{ \Carbon\Carbon::parse($task->date)->format('d/m/Y') }} 
                                        Hora: {{ \Carbon\Carbon::parse($task->time)->format('H:i') }}
                                    </small>
                                </div>
                                <div>
                                    <form action="{{ route('tasks.update', ['farm_id' => $farm->id, 'task' => $task]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $task->status ? '0' : '1' }}">
                                        <button type="submit" 
                                                class="btn btn-sm {{ $task->status ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $task->status ? 'Completada' : 'Pendiente' }}">
                                            <i class="fas {{ $task->status ? 'fa-check' : 'fa-times' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No tienes tareas asignadas en esta granja.
                        </div>
                    @endforelse
                @else
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
                                    <form action="{{ route('tasks.update', ['farm_id' => $farm->id, 'task' => $task]) }}" method="POST" class="me-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="{{ $task->status ? '0' : '1' }}">
                                        <button type="submit" 
                                                class="btn btn-sm {{ $task->status ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $task->status ? 'Completada' : 'Pendiente' }}">
                                            <i class="fas {{ $task->status ? 'fa-check' : 'fa-times' }}"></i>
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('tasks.destroy', ['farm_id' => $farm->id, 'task' => $task->id]) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta tarea?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No hay tareas asignadas en esta granja.
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Establecer la fecha actual como mínima en el campo de fecha
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('date')?.setAttribute('min', today);
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Obtener el formulario de asignación de tareas
    const taskForm = document.querySelector('form[action="{{ route('tasks.store', ['farm_id' => $farm->id]) }}"]');
    
    if (taskForm) {
        taskForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar confirmación antes de enviar
            Swal.fire({
                title: '¿Confirmar asignación?',
                text: '¿Estás seguro de que deseas asignar esta tarea?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, asignar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar indicador de carga
                    Swal.fire({
                        title: 'Asignando tarea...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Enviar el formulario
                    taskForm.submit();
                }
            });
        });
    }
});
</script>
@endpush
