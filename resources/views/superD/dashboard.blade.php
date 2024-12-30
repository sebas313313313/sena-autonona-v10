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
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Rol</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="user-info-cell">
                                            <div class="user-avatar">JP</div>
                                            <span>Juan Pérez</span>
                                        </div>
                                    </td>
                                    <td>juan@example.com</td>
                                    <td><span class="badge admin">Administrador</span></td>
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
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="farm-info">
                                            <i class="fa-solid fa-farm text-success"></i>
                                            <span>Granja El Paraíso</span>
                                        </div>
                                    </td>
                                    <td>Cundinamarca, Colombia</td>
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
                                <tr>
                                    <td>
                                        <div class="farm-info">
                                            <i class="fa-solid fa-farm text-success"></i>
                                            <span>Granja Los Rosales</span>
                                        </div>
                                    </td>
                                    <td>Boyacá, Colombia</td>
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
                                <tr>
                                    <td>
                                        <div class="farm-info">
                                            <i class="fa-solid fa-farm text-success"></i>
                                            <span>Granja San José</span>
                                        </div>
                                    </td>
                                    <td>Antioquia, Colombia</td>
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
                    <table>
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <i class="fa-solid fa-microchip text-success"></i>
                                        <span>Componente 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Editar" onclick="showSensorsModal()">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <i class="fa-solid fa-microchip text-success"></i>
                                        <span>Componente 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Editar" onclick="showSensorsModal()">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="user-info">
                                        <i class="fa-solid fa-microchip text-success"></i>
                                        <span>Componente 3</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-icon" title="Ver">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>
                                        <button class="btn-icon" title="Editar" onclick="showSensorsModal()">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="card-footer">
                        <button class="btn-action" onclick="showNewComponentForm()">
                            <i class="fa-solid fa-plus"></i> Nuevo
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
                        <button class="close-modal" onclick="hideNewComponentForm()">
                            <i class="fa-solid fa-times"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="newComponentForm">
                            <div class="form-group">
                                <div class="input-group">
                                    <i class="fa-solid fa-cube"></i>
                                    <input type="text" placeholder="Nombre del Componente" required>
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

            <!-- Modal de Sensores -->
            <div class="modal" id="sensorsModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Sensores</h3>
                        <button class="close-modal" onclick="hideSensorsModal()">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="sensors-list">
                            <div class="sensor-item">Sensor de Temperatura</div>
                            <div class="sensor-item">Sensor de Humedad</div>
                            <div class="sensor-item">Sensor de Presión</div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn-action" onclick="showNewSensorForm()">
                                <i class="fa-solid fa-plus"></i> Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Nuevo Sensor -->
            <div class="modal" id="newSensorModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Nuevo Sensor</h3>
                        <button class="close-modal" onclick="hideNewSensorForm()">×</button>
                    </div>
                    <div class="modal-body">
                        <form id="newSensorForm">
                            <div class="form-group">
                                <input type="text" placeholder="Nombre del Sensor" required>
                            </div>
                            <div class="form-actions">
                                <button type="button" onclick="hideNewSensorForm()">Cancelar</button>
                                <button type="submit">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="historial" class="content-section" style="display: none;">
                <!-- Contenido de historial aquí -->
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
/* Variables */
:root {
    --primary-color: #2C3E50;
    --secondary-color: #34495E;
    --accent-color: #3498DB;
    --success-color: #2ECC71;
    --warning-color: #F1C40F;
    --danger-color: #E74C3C;
    --text-color: #2C3E50;
    --text-light: #7F8C8D;
    --border-color: #ECF0F1;
    --background-light: #F8FAFC;
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
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
    z-index: 1100;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.modal-header {
    background: var(--accent-color);
    color: white;
    padding: 1.5rem;
    border-radius: 1rem 1rem 0 0;
    position: relative;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modal-title i {
    font-size: 1.5rem;
}

.modal-title h3 {
    margin: 0;
    font-size: 1.5rem;
}

.close-modal {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.close-modal:hover {
    background: rgba(255,255,255,0.2);
}

.modal-body {
    padding: 2rem;
}

.sensors-list {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
}

.sensor-item {
    padding: 0.75rem 1rem;
    background: var(--bg-light);
    border-radius: 0.5rem;
    transition: var(--transition);
}

.sensor-item:hover {
    background: var(--bg-lighter);
}

.modal-footer {
    padding: 1rem;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
}

.btn-action {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    background: var(--accent-color);
    color: white;
    font-size: 0.875rem;
    cursor: pointer;
    transition: var(--transition);
}

.btn-action:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Estilos del formulario */
.form-group {
    margin-bottom: 1.5rem;
}

.form-group .input-group {
    position: relative;
    display: flex;
    align-items: center;
}

.form-group .input-group i {
    position: absolute;
    left: 1rem;
    color: var(--text-light);
    transition: var(--transition);
}

.form-group input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 2px solid var(--border-color);
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: var(--transition);
}

.form-group input:focus {
    border-color: var(--accent-color);
    box-shadow: 0 0 0 4px rgba(52, 152, 219, 0.1);
}

.form-group input:focus + i {
    color: var(--accent-color);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 2rem;
}

.form-actions button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 0.75rem;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.form-actions .btn-primary {
    background: var(--accent-color);
    color: white;
}

.form-actions .btn-secondary {
    background: var(--border-color);
    color: var(--text-color);
}

.form-actions button:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Estilos del Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1100;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 1rem;
    width: 100%;
    max-width: 450px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.modal-header {
    background: var(--accent-color);
    color: white;
    padding: 1.5rem;
    border-radius: 1rem 1rem 0 0;
    position: relative;
}

.modal-title {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.modal-title i {
    font-size: 1.5rem;
}

.modal-title h3 {
    margin: 0;
    font-size: 1.5rem;
}

.close-modal {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.1);
    border: none;
    color: white;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
}

.close-modal:hover {
    background: rgba(255,255,255,0.2);
}

.modal-body {
    padding: 2rem;
}

/* Toggle password */
.toggle-password {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    color: var(--text-light);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: var(--transition);
}

.toggle-password:hover {
    background: rgba(0,0,0,0.05);
}
</style>
@endsection

@section('scripts')
<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    sidebar.classList.toggle('active');
}

function showSection(sectionId) {
    // Ocultar todas las secciones
    document.querySelectorAll('.content-section').forEach(section => {
        section.style.display = 'none';
    });
    
    // Mostrar la sección seleccionada
    document.getElementById(sectionId).style.display = 'block';
    
    // Actualizar clases activas en el menú
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

function showNewComponentForm() {
    document.getElementById('newComponentModal').classList.add('show');
}

function hideNewComponentForm() {
    document.getElementById('newComponentModal').classList.remove('show');
}

function showSensorsModal() {
    console.log('Intentando mostrar modal de sensores');
    const modal = document.getElementById('sensorsModal');
    console.log('Modal encontrado:', modal);
    modal.classList.add('show');
    console.log('Clase show agregada');
}

function hideSensorsModal() {
    document.getElementById('sensorsModal').classList.remove('show');
}

function showNewSensorForm() {
    document.getElementById('newSensorModal').classList.add('show');
}

function hideNewSensorForm() {
    document.getElementById('newSensorModal').classList.remove('show');
}

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

// Mostrar usuarios por defecto
document.addEventListener('DOMContentLoaded', function() {
    showSection('usuarios');
});
</script>
@endsection
