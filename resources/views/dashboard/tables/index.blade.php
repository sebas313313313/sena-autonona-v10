@extends('dashboard.layouts.main')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Usuarios de {{ $farm->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
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
            <h5 class="card-title mb-0">Usuarios Actuales</h5>
        </div>
        <div class="card-body">
            @if(count($users) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Correo</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->is_owner)
                                    <span class="badge bg-primary">Propietario</span>
                                @else
                                    <span class="badge bg-{{ $user->pivot->role == 'admin' ? 'info' : 'secondary' }}">
                                        {{ ucfirst($user->pivot->role) }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if(!$user->is_owner)
                                    <button class="btn btn-danger btn-sm" onclick="unlinkUser({{ $farm->id }}, {{ $user->id }})">
                                        Desvincular
                                    </button>
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
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Invitar Usuario</h5>
        </div>
        <div class="card-body">
            <form id="inviteForm" onsubmit="sendInvitation(event)">
                @csrf
                <input type="hidden" name="farm_id" value="{{ $farm->id }}">
                <input type="hidden" name="return_to" value="{{ route('dashboard.users', ['farm_id' => $farm->id]) }}">
                
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" name="email" required 
                           placeholder="Ingrese el correo electrónico del usuario">
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
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function sendInvitation(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);

    // Mostrar alerta de carga
    Swal.fire({
        title: 'Enviando invitación...',
        html: 'Por favor espere mientras se procesa la invitación',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Enviar petición
    fetch('{{ route('invitations.send') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Invitación enviada!',
                text: data.message,
                showConfirmButton: true
            }).then(() => {
                // Limpiar el formulario
                form.reset();
                // Recargar la página para mostrar los cambios
                window.location.reload();
            });
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Hubo un error al enviar la invitación. Por favor, intente nuevamente.'
        });
    });
}

function unlinkUser(farmId, userId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Deseas desvincular esta persona de la granja?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, desvincular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/dashboard/farm/${farmId}/unlink/${userId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire(
                        '¡Desvinculado!',
                        'El usuario ha sido desvinculado exitosamente.',
                        'success'
                    ).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire(
                        'Error',
                        data.message,
                        'error'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire(
                    'Error',
                    'Hubo un error al desvincular el usuario',
                    'error'
                );
            });
        }
    });
}
</script>
@endpush