@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tabla de Usuarios -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Usuarios de la Granja</h5>
        </div>
        <div class="card-body">
            @if(count($users ?? []) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Rol en la Granja</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->id === $owner->id)
                                    <span class="badge bg-primary">Propietario</span>
                                @else
                                    <span class="badge bg-info">{{ ucfirst($user->pivot->role ?? 'Invitado') }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info" role="alert">
                    No hay usuarios registrados en esta granja.
                </div>
            @endif
        </div>
    </div>

    <!-- Formulario de Invitación -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Enviar Invitación</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('invitation.sendByEmail') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Ingrese el correo electrónico">
                </div>
                
                <div class="mb-3">
                    <label for="role" class="form-label">Rol</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="" selected disabled>Seleccione un rol</option>
                        <option value="admin">Administrador</option>
                        <option value="operario">Operario</option>
                    </select>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-2"></i>Enviar Invitación
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
</div>
@endsection

@push('scripts')
@endpush