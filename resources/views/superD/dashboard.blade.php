@extends('layouts.main')

@section('title', 'Dashboard SuperD')

@section('content')
<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-container">
                <i class="fas fa-leaf fa-2x text-success"></i>
                <h3>AGROVIDA</h3>
            </div>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-item" onclick="showSection('usuarios')">
                <a href="#">
                    <i class="fa-solid fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            <li class="menu-item" onclick="showSection('granjas')">
                <a href="#">
                    <i class="fa-solid fa-warehouse"></i>
                    <span>Granjas</span>
                </a>
            </li>
            <li class="menu-item" onclick="showSection('componentes')">
                <a href="#">
                    <i class="fa-solid fa-microchip"></i>
                    <span>Componentes</span>
                </a>
            </li>
            <li class="menu-item" onclick="showSection('historial')">
                <a href="#">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Historial</span>
                </a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <nav class="top-bar">
            <button class="menu-toggle" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <div class="user-info">
                <span>Bienvenido, Admin</span>
                <i class="fas fa-user-circle"></i>
            </div>
        </nav>

        <!-- Content Sections -->
        <div class="content-wrapper">
            <div id="usuarios" class="content-section">
                <div class="section-header">
                    <h2>Gestión de Usuarios</h2>
                    <div class="header-actions">
                        <button class="btn-action" onclick="showNewUserForm()">
                            <i class="fa-solid fa-plus"></i> Nuevo Usuario
                        </button>
                    </div>
                </div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar">{{ substr($user->name, 0, 2) }}</div>
                                            <span>{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->email === 'super.d@example.com')
                                            <span class="badge admin">SuperD</span>
                                        @else
                                            <span class="badge admin">Activo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-icon" title="Ver">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon" title="Cambiar Contraseña" onclick="showChangePasswordModal()">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                            <button class="btn-icon" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon" title="Eliminar">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal Nuevo Usuario -->
                <div class="modal" id="newUserModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <i class="fa-solid fa-user-plus"></i>
                                <h3>Nuevo Super Usuario</h3>
                            </div>
                            <button class="close-modal" onclick="hideNewUserForm()">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="newUserForm">
                                <div class="form-group">
                                    <label>Correo Electrónico</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-envelope"></i>
                                        <input type="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Contraseña</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-lock"></i>
                                        <input type="password" required>
                                        <button type="button" class="toggle-password">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn-secondary" onclick="hideNewUserForm()">Cancelar</button>
                                    <button type="submit" class="btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Cambiar Contraseña -->
                <div class="modal" id="changePasswordModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <i class="fa-solid fa-key"></i>
                                <h3>Cambiar Contraseña</h3>
                            </div>
                            <button class="close-modal" onclick="hideChangePasswordModal()">×</button>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm">
                                <div class="form-group">
                                    <label>Nueva Contraseña</label>
                                    <div class="input-group">
                                        <i class="fa-solid fa-lock"></i>
                                        <input type="password" id="newPassword" required>
                                        <button type="button" class="toggle-password">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <button type="button" class="btn-secondary" onclick="hideChangePasswordModal()">Cancelar</button>
                                    <button type="submit" class="btn-primary">Aceptar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div id="granjas" class="content-section" style="display: none;">
                <div class="section-header">
                    <h2>Gestión de Granjas</h2>
                </div>

                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre de la Granja</th>
                                    <th>Ubicación</th>
                                    <th>Propietario</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($farms as $farm)
                                <tr>
                                    <td>
                                        <div class="farm-info">
                                            <i class="fa-solid fa-warehouse text-success"></i>
                                            <span>{{ $farm->name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        {{ optional($farm->municipality)->name }}, 
                                        {{ $farm->vereda }}
                                    </td>
                                    <td>
                                        {{ optional(optional($farm->usersRole)->user)->name ?? 'No asignado' }}
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-icon" title="Ver">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon" title="Editar">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="componentes" class="content-section" style="display: none;">
                <div class="section-header">
                    <h2>Gestión de Componentes</h2>
                </div>
                <div class="card">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($components as $component)
                                <tr data-component-id="{{ $component->id }}">
                                    <td>
                                        <div class="user-info">
                                            <i class="fa-solid fa-microchip text-success"></i>
                                            <span>{{ $component->description }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-icon" title="Ver">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon" title="Editar" onclick="showSensorsModal({{ $component->id }})">
                                                <i class="fa-solid fa-pen"></i>
                                            </button>
                                            <button class="btn-icon delete" title="Eliminar" onclick="deleteComponent({{ $component->id }})">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <button class="btn-action" onclick="showNewComponentForm()">
                            <i class="fa-solid fa-plus"></i> Nuevo Componente
                        </button>
                    </div>
                </div>
            </div>

            <!-- Modal de Sensores -->
            <div class="modal" id="sensorsModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title">
                            <i class="fa-solid fa-microchip"></i>
                            <h3>Sensores del Componente</h3>
                        </div>
                        <button class="close-modal" onclick="hideSensorsModal()">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="sensors-list">
                            <!-- Los sensores se cargarán dinámicamente aquí -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn-action" onclick="showNewSensorForm()">
                            <i class="fa-solid fa-plus"></i> Agregar Sensor
                        </button>
                    </div>
                </div>
            </div>

<!-- Modal Nuevo Componente -->
<div class="modal" id="newComponentModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fa-solid fa-microchip"></i>
                <h3>Nuevo Componente</h3>
            </div>
            <button class="close-modal" onclick="hideNewComponentForm()">×</button>
        </div>
        <div class="modal-body">
            <form id="newComponentForm" method="POST" action="{{ route('components.store') }}">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa-solid fa-cube"></i>
                        <input type="text" name="description" placeholder="Nombre del Componente" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="hideNewComponentForm()">
                        <i class="fa-solid fa-times"></i>
                        <span>Cancelar</span>
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-check"></i>
                        <span>Guardar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para agregar nuevo sensor -->
<div class="modal" id="newSensorModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fa-solid fa-wave-square"></i>
                <h3>Nuevo Sensor</h3>
            </div>
            <button class="close-modal" onclick="hideNewSensorForm()">×</button>
        </div>
        <div class="modal-body">
            <form id="newSensorForm">
                <input type="hidden" id="sensorComponentId" name="component_id">
                <div class="form-group">
                    <label>Nombre del Sensor</label>
                    <div class="input-group">
                        <i class="fa-solid fa-tag"></i>
                        <input type="text" name="name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <div class="input-group">
                        <i class="fa-solid fa-align-left"></i>
                        <input type="text" name="description" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Unidad de Medida</label>
                    <div class="input-group">
                        <i class="fa-solid fa-ruler"></i>
                        <input type="text" name="unit" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="hideNewSensorForm()">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sección de historial -->
<div id="historial" class="content-section" style="display: none;">
    <!-- Contenido de historial aquí -->
</div>

@section('styles')
<style>
/* Variables */
:root {
    --primary-color: #2C3E50;
    --secondary-color: #34495E;
    --accent-color: #3498DB;
    --accent-darker: #2980B9;
    --success-color: #2ECC71;
    --warning-color: #F1C40F;
    --danger-color: #E74C3C;
    --text-color: #2C3E50;
    --text-light: #7F8C8D;
    --border-color: #ECF0F1;
    --background-light: #F8FAFC;
    --transition: all 0.3s ease;
}

/* Layout Base */
.dashboard-container {
    display: flex;
    min-height: 100vh;
    background-color: var(--background-light);
}

/* Sidebar */
.sidebar {
    width: 260px;
    background: var(--primary-color);
    color: white;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
    position: fixed;
    height: 100vh;
    z-index: 1000;
}

.sidebar-header {
    padding: 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.logo-container {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-container h3 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.sidebar-menu {
    list-style: none;
    padding: 1rem 0;
    margin: 0;
}

.menu-item {
    padding: 0.5rem 1.5rem;
    margin: 0.2rem 0;
}

.menu-item a {
    color: rgba(255,255,255,0.8);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.7rem 1rem;
    border-radius: 0.5rem;
    transition: var(--transition);
}

.menu-item.active a,
.menu-item a:hover {
    background: rgba(255,255,255,0.1);
    color: white;
}

.sidebar-footer {
    margin-top: auto;
    padding: 1.5rem;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.logout-btn {
    width: 100%;
    padding: 0.8rem;
    background: rgba(231, 76, 60, 0.1);
    color: #E74C3C;
    border: none;
    border-radius: 0.5rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.logout-btn:hover {
    background: rgba(231, 76, 60, 0.2);
}

/* Main Content */
.main-content {
    flex: 1;
    margin-left: 260px;
    transition: var(--transition);
}

.top-bar {
    background: white;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: var(--shadow-sm);
}

.menu-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-color);
    cursor: pointer;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--text-color);
}

.content-wrapper {
    padding: 2rem;
}

/* Cards y Tablas */
.card {
    background: white;
    border-radius: 0.5rem;
    box-shadow: var(--shadow-md);
    margin-bottom: 2rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

.btn-action {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    background: var(--accent-color);
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
}

.btn-action:hover {
    opacity: 0.9;
}

.btn-action.secondary {
    background: var(--secondary-color);
}

.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.table th {
    background: var(--background-light);
    font-weight: 600;
    color: var(--text-color);
}

.user-info-cell {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-avatar {
    width: 35px;
    height: 35px;
    background: var(--accent-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.badge.admin {
    background: rgba(52, 152, 219, 0.1);
    color: var(--accent-color);
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
}

.status-badge.active {
    background: rgba(46, 204, 113, 0.1);
    color: var(--success-color);
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 0.5rem;
    background: var(--background-light);
    color: var(--text-color);
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-icon:hover {
    background: var(--border-color);
}

.btn-icon.delete:hover {
    background: rgba(231, 76, 60, 0.1);
    color: var(--danger-color);
}

/* Estilos para granjas */
.farm-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.farm-info i {
    font-size: 1.2rem;
    color: var(--success-color);
}

.badge.success {
    background: rgba(46, 204, 113, 0.1);
    color: var(--success-color);
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex !important;
}

#newSensorModal {
    z-index: 1100; /* Mayor que el modal de sensores */
}

.modal-content {
    background: white;
    border-radius: 8px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1rem;
    background: var(--accent-color);
    color: white;
    border-radius: 8px 8px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.modal-title i {
    font-size: 1.2rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 1rem;
}

.input-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem;
    border: 1px solid var(--border-color);
    border-radius: 4px;
}

.input-group i {
    color: var(--text-light);
}

.input-group input {
    border: none;
    outline: none;
    width: 100%;
    font-size: 1rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
}

.close-modal {
    background: none;
    border: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s;
}

.close-modal:hover {
    transform: scale(1.1);
}

.sensors-list {
    display: grid;
    gap: 1rem;
}

.sensor-item {
    background: white;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 1rem;
}

.sensor-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-color);
}

.sensor-info p {
    margin: 0 0 0.5rem 0;
    color: var(--text-light);
}

.sensor-info .unit {
    display: inline-block;
    background: var(--background-light);
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
    color: var(--text-light);
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--accent-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.btn-action:hover {
    background: var(--accent-darker);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: white;
    color: var(--text-color);
    border: 1px solid var(--border-color);
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background: var(--background-light);
}

.btn-primary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: var(--accent-color);
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.btn-primary:hover {
    background: var(--accent-darker);
}
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mostrar sección predeterminada
        showSection('usuarios');

        // Toggle password visibility para todos los campos de contraseña
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);

                // Cambiar el ícono
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });

        // Manejar el formulario de nuevo componente
        const form = document.getElementById('newComponentForm');
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                try {
                    const formData = new FormData(this);
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    Swal.fire({
                        title: 'Creando componente...',
                        text: 'Por favor espere...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    const response = await fetch('/components', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ description: formData.get('description') })
                    });

                    const data = await response.json();
                    if (!response.ok) throw new Error(data.message || 'Error al crear el componente');

                    form.reset();
                    hideNewComponentForm();

                    await Swal.fire({
                        icon: 'success',
                        title: '¡Éxito!',
                        text: 'El componente se ha creado correctamente',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#3498DB'
                    });

                    window.location.reload();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Hubo un error al crear el componente. Por favor, inténtelo de nuevo.',
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#3498DB'
                    });
                }
            });
        }
    });

    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        sidebar.classList.toggle('active');
    }

    function showSection(sectionId) {
        document.querySelectorAll('.content-section').forEach(section => {
            section.style.display = 'none';
        });

        document.getElementById(sectionId).style.display = 'block';

        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('onclick').includes(sectionId)) {
                item.classList.add('active');
            }
        });
    }

    function showNewUserForm() {
        document.getElementById('newUserModal').classList.add('show');
    }

    function hideNewUserForm() {
        document.getElementById('newUserModal').classList.remove('show');
    }

    function showChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.add('show');
    }

    function hideChangePasswordModal() {
        document.getElementById('changePasswordModal').classList.remove('show');
    }

    function showSensorsModal(componentId) {
        const modal = document.getElementById('sensorsModal');
        const sensorsList = modal.querySelector('.sensors-list');

        sensorsList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando sensores...</div>';
        modal.classList.add('show');

        fetch(`/superD/components/${componentId}/sensors`, {
            headers: { 'Accept': 'application/json' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sensorsList.innerHTML = data.sensors.length > 0 
                    ? data.sensors.map(sensor => `<div class="sensor-item"><div class="sensor-info"><i class="fa-solid fa-wave-square text-success"></i><span>${sensor.description}</span></div></div>`).join('') 
                    : '<div class="text-center">No hay sensores asociados a este componente</div>';
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            sensorsList.innerHTML = `<div class="text-center text-danger">Error al cargar los sensores: ${error.message}</div>`;
        });
    }

    function hideSensorsModal() {
        document.getElementById('sensorsModal').classList.remove('show');
    }

    function deleteComponent(componentId) {
        if (confirm('¿Está seguro de que desea eliminar este componente?')) {
            fetch(`/superD/components/${componentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.querySelector(`tr[data-component-id="${componentId}"]`)?.remove();
                    alert(data.message);
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                alert('Error al eliminar el componente: ' + error.message);
            });
        }
    }

    function showNewComponentForm() {
        document.getElementById('newComponentModal').classList.add('show');
    }

    function hideNewComponentForm() {
        document.getElementById('newComponentModal').classList.remove('show');
    }

    function showNewSensorForm() {
        const currentComponentId = document.getElementById('sensorComponentId').value;
        if (!currentComponentId) {
            console.error('No hay componente seleccionado');
            return;
        }
        document.getElementById('newSensorModal').classList.add('show');
    }

    function hideNewSensorForm() {
        document.getElementById('newSensorModal').classList.remove('show');
    }
</script>
@endsection
