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
            <li class="menu-item" onclick="showSection('otros')">
                <a href="#">
                    <i class="fa-solid fa-gear"></i>
                    <span>Otros</span>
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
                        <button class="btn-action" onclick="showNewSuperDModal()">
                            <i class="fa-solid fa-plus"></i> Nuevo SuperD
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
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="usersTableBody">
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
                                        <div class="action-buttons">
                                            <button class="btn-icon" title="Ver" onclick="showUserDetails({{ $user->id }})">
                                                <i class="fa-solid fa-eye"></i>
                                            </button>
                                            <button class="btn-icon" title="Cambiar Contraseña" onclick="showChangePasswordModal({{ $user->id }})">
                                                <i class="fa-solid fa-key"></i>
                                            </button>
                                            <button class="btn-icon" title="Descargar Historial">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                                            <button class="btn-icon" title="Eliminar" onclick="deleteUser({{ $user->id }}, '{{ $user->email }}')">
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

                <!-- Modal Nuevo SuperD -->
                <div class="modal" id="newSuperDModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="modal-title">
                                <i class="fa-solid fa-user-shield"></i>
                                <h2>Nuevo SuperD</h2>
                            </div>
                            <span class="close" onclick="closeNewSuperDModal()">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="newSuperDForm" onsubmit="handleCreateSuperD(event)">
                                <div class="form-group">
                                    <label for="superDEmail">Correo Electrónico</label>
                                    <input type="email" id="superDEmail" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="superDPassword">Contraseña</label>
                                    <input type="password" id="superDPassword" name="password" required>
                                </div>
                                <div class="button-group">
                                    <button type="button" class="btn-secondary" onclick="closeNewSuperDModal()">
                                        <i class="fa-solid fa-times"></i>
                                        <span>Cancelar</span>
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Crear SuperD</span>
                                    </button>
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
                                <h2>Cambiar Contraseña</h2>
                            </div>
                            <span class="close" onclick="closeChangePasswordModal()">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="changePasswordForm" onsubmit="handleChangePassword(event)">
                                <input type="hidden" id="userId" name="userId">
                                <div class="form-group">
                                    <label for="newPassword">Nueva Contraseña</label>
                                    <input type="password" id="newPassword" name="newPassword" required>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Confirmar Contraseña</label>
                                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <div class="button-group">
                                    <button type="button" class="btn-secondary" onclick="closeChangePasswordModal()">
                                        <i class="fa-solid fa-times"></i>
                                        <span>Cancelar</span>
                                    </button>
                                    <button type="submit" class="btn-primary">
                                        <i class="fa-solid fa-check"></i>
                                        <span>Guardar Cambios</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Ver Usuario -->
                <div class="modal" id="userDetailsModal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h2>Detalles del Usuario</h2>
                            <button class="close-modal" onclick="document.getElementById('userDetailsModal').style.display='none'">×</button>
                        </div>
                        <div class="modal-body">
                            <div class="user-details">
                                <p><strong>Nombre:</strong> <span id="userDetailsName"></span></p>
                                <p><strong>Apellido:</strong> <span id="userDetailsLastName"></span></p>
                                <p><strong>Correo:</strong> <span id="userDetailsEmail"></span></p>
                                <p><strong>Identificación:</strong> <span id="userDetailsIdentification"></span></p>
                                <p><strong>Teléfono:</strong> <span id="userDetailsPhone"></span></p>
                                <p><strong>Dirección:</strong> <span id="userDetailsAddress"></span></p>
                                <p><strong>Rol:</strong> <span id="userDetailsRoles"></span></p>
                                <p><strong>Fecha de Registro:</strong> <span id="userDetailsCreatedAt"></span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    .user-details {
                        padding: 20px;
                    }
                    .detail-group {
                        margin-bottom: 15px;
                    }
                    .detail-group label {
                        font-weight: bold;
                        display: inline-block;
                        width: 180px;
                        margin-right: 10px;
                    }
                    .detail-group span {
                        color: #666;
                    }
                    #userDetailSecurityQuestions {
                        margin-top: 10px;
                        padding-left: 180px;
                    }
                    #userDetailSecurityQuestions .question {
                        margin-bottom: 10px;
                        padding: 5px;
                        background: #f5f5f5;
                        border-radius: 4px;
                    }
                    #userDetailSecurityQuestions .question strong {
                        display: block;
                        margin-bottom: 5px;
                    }
                </style>

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
                                            <button class="btn-icon" title="Descargar Historial">
                                                <i class="fa-solid fa-download"></i>
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
                            <tbody id="componentsTable">
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
            <form id="newComponentForm" onsubmit="handleNewComponent(event)">
                @csrf
                <div class="form-group">
                    <div class="input-group">
                        <i class="fa-solid fa-cube"></i>
                        <input type="text" name="description" placeholder="Nombre del Componente" required>
                    </div>
                </div>
                <div class="button-group">
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
                <div class="button-group">
                    <button type="button" class="btn-secondary" onclick="hideNewSensorForm()">Cancelar</button>
                    <button type="submit" class="btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Sección de historial -->
<div id="historial" class="content-section" style="display: none;">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historial</h3>
            <div class="card-tools">
                <a href="{{ route('activity-log.download') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-download"></i> Descargar Historial
                </a>
            </div>
        </div>
        <div class="card-body">
            <!-- Aquí va el contenido del historial -->
        </div>
    </div>
</div>

<!-- Sección de otros -->
<div id="otros" class="content-section" style="display: none;">
    <div class="section-header">
        <h2>Otros</h2>
        <p>Configuraciones adicionales y herramientas del sistema</p>
    </div>

    <div class="cards-container">
        <!-- Card para Tipos de Identificación -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-id-card"></i>
                <h3>Tipos de Identificación</h3>
            </div>
            <div class="card-body">
                <p>Gestiona los tipos de identificación disponibles en el sistema.</p>
                <button class="btn-primary" onclick="showIdentificationTypes()">
                    <i class="fa-solid fa-eye"></i> Ver Tipos
                </button>
            </div>
        </div>

        <!-- Card para Municipios -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-map-location-dot"></i>
                <h3>Municipios</h3>
            </div>
            <div class="card-body">
                <p>Administra los municipios registrados en el sistema.</p>
                <button class="btn-primary" onclick="showMunicipalities()">
                    <i class="fa-solid fa-eye"></i> Ver Municipios
                </button>
            </div>
        </div>

        <!-- Card para Preguntas de Seguridad -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-shield-halved"></i>
                <h3>Preguntas de Seguridad</h3>
            </div>
            <div class="card-body">
                <p>Gestiona las preguntas de seguridad para recuperación de cuentas.</p>
                <button class="btn-primary" onclick="showSecurityQuestions()">
                    <i class="fa-solid fa-eye"></i> Ver Preguntas
                </button>
            </div>
        </div>

        <!-- Card para Roles de Usuario -->
        <div class="card">
            <div class="card-header">
                <i class="fa-solid fa-user-tag"></i>
                <h3>Roles de Usuario</h3>
            </div>
            <div class="card-body">
                <p>Administra los roles y permisos de los usuarios.</p>
                <button class="btn-primary" onclick="showUserRoles()">
                    <i class="fa-solid fa-eye"></i> Ver Roles
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para Tipos de Identificación -->
    <div id="identificationTypesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Tipos de Identificación</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="identificationTypesTable">
                        <!-- Datos de tipos de identificación -->
                    </tbody>
                </table>
                <button class="btn-action" onclick="showNewIdentificationTypeForm()">
                    <i class="fa-solid fa-plus"></i> Nuevo Tipo de Identificación
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para Municipios -->
    <div id="municipalitiesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Municipios</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="municipalitiesTable">
                        <!-- Datos de municipios -->
                    </tbody>
                </table>
                <button class="btn-action" onclick="showNewMunicipalityForm()">
                    <i class="fa-solid fa-plus"></i> Nuevo Municipio
                </button>
            </div>
        </div>
    </div>

    <!-- Modal para Preguntas de Seguridad -->
    <div id="securityQuestionsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Preguntas de Seguridad</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <div class="modal-actions">
                    <button class="btn-primary" onclick="showNewSecurityQuestionForm()">
                        <i class="fa-solid fa-plus"></i> Nueva Pregunta
                    </button>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pregunta</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="securityQuestionsTable">
                        <!-- Datos de preguntas de seguridad -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para Nueva Pregunta de Seguridad -->
    <div id="newSecurityQuestionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nueva Pregunta de Seguridad</h2>
                <span class="close" onclick="hideNewSecurityQuestionForm()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="newSecurityQuestionForm">
                    @csrf
                    <div class="form-group">
                        <label>Pregunta</label>
                        <div class="input-group">
                            <i class="fa-solid fa-question"></i>
                            <input type="text" name="question" required placeholder="Ingrese la pregunta de seguridad">
                        </div>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="hideNewSecurityQuestionForm()">Cancelar</button>
                        <button type="submit" class="btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para Roles de Usuario -->
    <div id="userRolesModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Roles de Usuario</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="userRolesTable">
                        <!-- Datos de roles de usuario -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para nuevo tipo de identificación -->
    <div class="modal" id="newIdentificationTypeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nuevo Tipo de Identificación</h2>
                <span class="close" onclick="hideModal('newIdentificationTypeModal')">&times;</span>
            </div>
            <form id="newIdentificationTypeForm" onsubmit="handleNewIdentificationType(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <input type="text" id="description" name="description" required>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="hideModal('newIdentificationTypeModal')">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal para Nuevo Municipio -->
    <div id="newMunicipalityModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Nuevo Municipio</h2>
                <span class="close" onclick="hideModal('newMunicipalityModal')">&times;</span>
            </div>
            <form id="newMunicipalityForm" onsubmit="handleNewMunicipality(event)">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre del Municipio:</label>
                        <input type="text" id="name" name="name" required>
                    </div>
                    <div class="button-group">
                        <button type="button" class="btn-secondary" onclick="hideModal('newMunicipalityModal')">
                            <i class="fa-solid fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-primary">
                            <i class="fa-solid fa-save"></i> Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showNewIdentificationTypeForm() {
            document.getElementById('newIdentificationTypeModal').style.display = 'block';
        }

        function handleNewIdentificationType(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const description = formData.get('description');

            fetch('/api/identification-types', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    description: description
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Error al crear el tipo de identificación');
                }
                return data;
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Tipo de identificación creado exitosamente'
                });
                form.reset();
                hideModal('newIdentificationTypeModal');
                showIdentificationTypes();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al crear el tipo de identificación'
                });
            });
        }
    </script>

    <script>
        function showNewMunicipalityForm() {
            document.getElementById('newMunicipalityModal').style.display = 'block';
        }

        function handleNewMunicipality(event) {
            event.preventDefault();
            
            const form = event.target;
            const formData = new FormData(form);
            const name = formData.get('name');

            fetch('/api/municipalities/create', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: name
                })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Error al crear el municipio');
                }
                return data;
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: 'Municipio creado exitosamente'
                });
                form.reset();
                hideModal('newMunicipalityModal');
                showMunicipalities();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Error al crear el municipio'
                });
            });
        }
    </script>
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
    transition: background-color 0.3s;
}

.btn-action:hover {
    background-color: var(--accent-darker);
}

.btn-secondary {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 0.5rem;
    background: #6c757d;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: background-color 0.3s;
}

.btn-secondary:hover {
    background-color: #5a6268;
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
}

.modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1rem;
    background: var(--accent-color);
    color: white;
    border-radius: 10px 10px 0 0;
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
    padding: 20px;
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

.button-group {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 20px;
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
    background: var(--accent-color) !important;
    color: white !important;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
    max-width: 200px;
}

.btn-action:hover {
    background-color: var(--accent-darker);
}

.btn-secondary {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 0.875rem;
    transition: background-color 0.2s;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

#otros .cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

#otros .card {
    background: var(--white);
    border-radius: 10px;
    box-shadow: var(--card-shadow);
    transition: transform 0.3s ease;
}

#otros .card:hover {
    transform: translateY(-5px);
}

#otros .card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    gap: 1rem;
}

#otros .card-header i {
    font-size: 1.5rem;
    color: var(--primary);
}

#otros .card-header h3 {
    margin: 0;
    color: var(--text-dark);
}

#otros .card-body {
    padding: 1.5rem;
}

#otros .card-body p {
    color: var(--text-gray);
    margin-bottom: 1rem;
}

#otros .btn-primary {
    width: fit-content;
    padding: 0.75rem;
    border: none;
    border-radius: 5px;
    background: var(--primary);
    color: var(--white);
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    transition: background 0.3s ease;
}

#otros .btn-primary:hover {
    background: var(--primary-dark);
}

.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    background: var(--white);
    border-radius: 10px;
    width: 90%;
    max-width: 800px;
    margin: auto;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 10px;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    color: var(--text-dark);
}

.modal-body {
    padding: 20px;
}

.close {
    font-size: 1.5rem;
    cursor: pointer;
    color: var(--text-gray);
}

.close:hover {
    color: var(--text-dark);
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.data-table th,
.data-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--border-color);
}

.data-table th {
    background: var(--background);
    color: var(--text-dark);
    font-weight: 600;
}

.data-table tr:hover {
    background: var(--hover-color);
}

.modal-actions {
    margin-bottom: 1rem;
    display: flex;
    justify-content: flex-end;
}

.modal-actions .btn-primary {
    padding: 0.5rem 1rem;
    display: inline-flex;
    width: auto;
}

.btn-primary {
    background-color: #3498DB !important;
    color: white !important;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: background-color 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    width: fit-content;
    justify-content: center;
    max-width: 200px;
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

        // Manejar el formulario de nuevo compuuonente
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

                    const response = await fetch('/api/component/create', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ description: formData.get('description') })
                    });

                    const data = await response.json();
                    if (!response.ok) {
                        throw new Error(data.message || 'Error al crear el componente');
                    }
                    return data;
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Error al crear el componente. Por favor, inténtelo de nuevo.'
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
        // Ocultar todas las secciones
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            if (section && section.classList) {
                section.classList.remove('active');
            }
        });

        // Mostrar la sección seleccionada
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection && selectedSection.classList) {
            selectedSection.classList.add('active');
        }

        // Actualizar el botón activo
        const buttons = document.querySelectorAll('.nav-button');
        buttons.forEach(button => {
            if (button && button.classList) {
                button.classList.remove('active');
                if (button.getAttribute('data-section') === sectionId) {
                    button.classList.add('active');
                }
            }
        });
    }

    function showNewSuperDModal() {
        document.getElementById('newSuperDModal').style.display = 'block';
    }

    function closeNewSuperDModal() {
        document.getElementById('newSuperDModal').style.display = 'none';
        document.getElementById('newSuperDForm').reset();
    }

    function showChangePasswordModal(userId) {
        document.getElementById('userId').value = userId;
        document.getElementById('changePasswordModal').style.display = 'block';
    }

    function closeChangePasswordModal() {
        document.getElementById('changePasswordModal').style.display = 'none';
        document.getElementById('changePasswordForm').reset();
    }

    function handleChangePassword(event) {
        event.preventDefault();
        
        const userId = document.getElementById('userId').value;
        const newPassword = document.getElementById('newPassword').value;
        const confirmPassword = document.getElementById('confirmPassword').value;

        if (newPassword !== confirmPassword) {
            alert('Las contraseñas no coinciden');
            return;
        }

        fetch(`/superD/users/${userId}/change-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                password: newPassword
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cambiar la contraseña');
            }
            return response.json();
        })
        .then(data => {
            alert('Contraseña cambiada exitosamente');
            closeChangePasswordModal();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar la contraseña');
        });
    }

    function handleCreateSuperD(event) {
        event.preventDefault();
        
        const email = document.getElementById('superDEmail').value;
        const password = document.getElementById('superDPassword').value;

        fetch('/superD/create-superd', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                email: email,
                password: password
            })
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                // Si es un error de validación (422)
                if (response.status === 422) {
                    const errorMessages = Object.values(data.error).flat().join('\n');
                    throw new Error(errorMessages);
                }
                throw new Error(data.error || 'Error al crear SuperD');
            }
            return data;
        })
        .then(data => {
            alert('SuperD creado exitosamente');
            closeNewSuperDModal();
            // Recargar la lista de usuarios
            loadUsers();
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Error al crear SuperD');
        });
    }

    function showSensorsModal(componentId) {
        const modal = document.getElementById('sensorsModal');
        const sensorsList = modal.querySelector('.sensors-list');

        sensorsList.innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando sensores...</div>';
        modal.style.display = 'block';

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
        document.getElementById('sensorsModal').style.display = 'none';
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
        document.getElementById('newComponentModal').style.display = 'block';
    }

    function hideNewComponentForm() {
        document.getElementById('newComponentModal').style.display = 'none';
    }

    function showNewSensorForm() {
        const currentComponentId = document.getElementById('sensorComponentId').value;
        if (!currentComponentId) {
            console.error('No hay componente seleccionado');
            return;
        }
        document.getElementById('newSensorModal').style.display = 'block';
    }

    function hideNewSensorForm() {
        document.getElementById('newSensorModal').style.display = 'none';
    }

    // Funciones para mostrar/ocultar secciones
<<<<<<< HEAD
    // Eliminar la función duplicada que estaba aquí
=======
    function showSection(sectionId) {
        // Ocultar todas las secciones
        const sections = document.querySelectorAll('.content-section');
        sections.forEach(section => {
            if (section && section.classList) {
                section.classList.remove('active');
            }
        });

        // Mostrar la sección seleccionada
        const selectedSection = document.getElementById(sectionId);
        if (selectedSection && selectedSection.classList) {
            selectedSection.classList.add('active');
        }

        // Actualizar clases activas en el menú
        const buttons = document.querySelectorAll('.nav-button');
        buttons.forEach(button => {
            if (button && button.classList) {
                button.classList.remove('active');
                if (button.getAttribute('data-section') === sectionId) {
                    button.classList.add('active');
                }
            }
        });
    }
>>>>>>> 66b33eb1f81f89bc1e419e979bf139de4a9cdf67

    // Funciones para los modales de la sección Otros
    function showModal(modalId) {
        document.getElementById(modalId).style.display = 'block';
    }

    function hideModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Cerrar modales al hacer click fuera de ellos
    window.onclick = function(event) {
        if (event.target.classList.contains('modal')) {
            event.target.style.display = 'none';
        }
    }

    // Agregar evento de cierre a todos los botones de cerrar
    document.querySelectorAll('.close').forEach(closeBtn => {
        closeBtn.onclick = function() {
            this.closest('.modal').style.display = 'none';
        }
    });

    // Funciones para mostrar cada tipo de datos
    function showIdentificationTypes() {
        showModal('identificationTypesModal');
        fetch('/api/identification-types')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('identificationTypesTable');
                tbody.innerHTML = data.data.map(type => `
                    <tr>
                        <td>${type.id}</td>
                        <td>${type.description}</td>
                        <td>
                            <button class="btn-edit" onclick="editIdentificationType(${type.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteIdentificationType(${type.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            });
    }

    function showMunicipalities() {
        showModal('municipalitiesModal');
        fetch('/api/municipalities/index')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('municipalitiesTable');
                tbody.innerHTML = data.data.map(municipality => `
                    <tr>
                        <td>${municipality.id}</td>
                        <td>${municipality.name}</td>
                        <td>
                            <button class="btn-edit" onclick="editMunicipality(${municipality.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteMunicipality(${municipality.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los municipios'
                });
            });
    }

    function showSecurityQuestions() {
        showModal('securityQuestionsModal');
        fetch('{{ route("security-questions.index") }}')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('securityQuestionsTable');
                tbody.innerHTML = data.map(question => `
                    <tr>
                        <td>${question.id}</td>
                        <td>${question.question}</td>
                        <td>
                            <button class="btn-edit" onclick="editSecurityQuestion(${question.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteSecurityQuestion(${question.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar las preguntas de seguridad'
                });
            });
    }

    function showUserRoles() {
        showModal('userRolesModal');
        fetch('/api/user-roles')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('userRolesTable');
                tbody.innerHTML = data.map(role => `
                    <tr>
                        <td>${role.id}</td>
                        <td>${role.name}</td>
                        <td>${role.description}</td>
                        <td>
                            <button class="btn-edit" onclick="editUserRole(${role.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteUserRole(${role.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            });
    }

    // Funciones CRUD (a implementar según necesidad)
    function editIdentificationType(id) {
        // Implementar edición
        console.log('Editar tipo de identificación:', id);
    }

    function deleteIdentificationType(id) {
        // Implementar eliminación
        console.log('Eliminar tipo de identificación:', id);
    }

    function editMunicipality(id) {
        // Implementar edición
        console.log('Editar municipio:', id);
    }

    function deleteMunicipality(id) {
        // Implementar eliminación
        console.log('Eliminar municipio:', id);
    }

    function editSecurityQuestion(id) {
        // Implementar edición
        console.log('Editar pregunta de seguridad:', id);
    }

    function deleteSecurityQuestion(id) {
        // Implementar eliminación
        console.log('Eliminar pregunta de seguridad:', id);
    }

    function editUserRole(id) {
        // Implementar edición
        console.log('Editar rol de usuario:', id);
    }

    function deleteUserRole(id) {
        // Implementar eliminación
        console.log('Eliminar rol de usuario:', id);
    }

    // Funciones para las preguntas de seguridad
    function showNewSecurityQuestionForm() {
        document.getElementById('newSecurityQuestionModal').style.display = 'block';
    }

    function hideNewSecurityQuestionForm() {
        document.getElementById('newSecurityQuestionModal').style.display = 'none';
        document.getElementById('newSecurityQuestionForm').reset();
    }

    // Manejar el envío del formulario de nueva pregunta
    document.getElementById('newSecurityQuestionForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("security-questions.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(Object.fromEntries(formData))
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Pregunta de seguridad agregada exitosamente');
                hideNewSecurityQuestionForm();
                showSecurityQuestions(); // Recargar la lista
            } else {
                alert('Error al agregar la pregunta: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al agregar la pregunta de seguridad');
        });
    });

    function deleteSecurityQuestion(id) {
        if (confirm('¿Estás seguro de que deseas eliminar esta pregunta de seguridad?')) {
            fetch(`{{ url('superD/security-questions') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pregunta de seguridad eliminada exitosamente');
                    showSecurityQuestions(); // Recargar la lista
                } else {
                    alert('Error al eliminar la pregunta: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar la pregunta de seguridad');
            });
        }
    }

    function loadUsers() {
        fetch('/superD/users')
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Error al cargar los usuarios');
                }
                return data;
            })
            .then(users => {
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = users.map(user => `
                    <tr>
                        <td>${user.id}</td>
                        <td>${user.name}</td>
                        <td>${user.email}</td>
                        <td>
                            <button class="btn-action" onclick="showUserDetails(${user.id})">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button class="btn-action" onclick="showChangePasswordModal(${user.id})">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button class="btn-icon" title="Descargar Historial">
                                                <i class="fa-solid fa-download"></i>
                                            </button>
                            <button class="btn-icon" title="Eliminar" onclick="deleteUser(${user.id}, '${user.email}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                // Solo mostrar alerta si realmente hay un error
                if (!document.getElementById('usersTableBody').innerHTML) {
                    alert(error.message || 'Error al cargar los usuarios');
                }
            });
    }

    function deleteUser(userId, userEmail) {
        // No permitir eliminar al SuperD principal
        if (userEmail === 'super.d@example.com') {
            alert('No se puede eliminar al SuperD principal');
            return;
        }

        if (!confirm('¿Está seguro de que desea eliminar este usuario?')) {
            return;
        }

        fetch(`/superD/users/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) {
                throw new Error(data.error || 'Error al eliminar usuario');
            }
            return data;
        })
        .then(data => {
            alert('Usuario eliminado exitosamente');
            loadUsers(); // Recargar la lista de usuarios
        })
        .catch(error => {
            console.error('Error:', error);
            alert(error.message || 'Error al eliminar usuario');
        });
    }

    function showUserDetails(userId) {
        fetch(`/superD/users/${userId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al obtener los datos del usuario');
            }
            return response.json();
        })
        .then(response => {
            if (!response.success) {
                throw new Error(response.message || 'Error al obtener los datos del usuario');
            }
            
            const data = response.data;
            document.getElementById('userDetailsName').textContent = data.name || 'No especificado';
            document.getElementById('userDetailsLastName').textContent = data.last_name || 'No especificado';
            document.getElementById('userDetailsEmail').textContent = data.email || 'No especificado';
            document.getElementById('userDetailsIdentification').textContent = data.identification || 'No especificado';
            document.getElementById('userDetailsPhone').textContent = data.phone || 'No especificado';
            document.getElementById('userDetailsAddress').textContent = data.address || 'No especificada';
            document.getElementById('userDetailsRoles').textContent = data.roles || 'Usuario';
            document.getElementById('userDetailsCreatedAt').textContent = data.created_at || 'No especificado';
            
            document.getElementById('userDetailsModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Error al obtener los datos del usuario'
            });
        });
    }
    function showNewComponentForm() {
    document.getElementById('newComponentModal').style.display = 'block';
}

function handleNewComponent(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    const description = formData.get('description');

    fetch('/api/component/create', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            description: description
        })
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.message || 'Error al crear el componente');
        }
        return data;
    })
    .then(data => {
        hideNewComponentForm(); // Primero cerramos el modal
        Swal.fire({ // Luego mostramos el mensaje de éxito
            icon: 'success',
            title: '¡Éxito!',
            text: 'Componente creado exitosamente'
        });
        form.reset();
        showComponents();
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: error.message || 'Error al crear el componente'
        });
    });
    }
    
    function showComponents() {
        fetch('/api/component/index')
            .then(response => response.json())
            .then(data => {
                console.log('Respuesta del servidor:', data); // Para ver la estructura
                const tbody = document.getElementById('componentsTable');
                // Verificar si data es un array o si está dentro de una propiedad
                const components = Array.isArray(data) ? data : (data.components || data.data || []);
                console.log('Componentes a mostrar:', components); // Para verificar los datos
                tbody.innerHTML = components.map(component => `
                    <tr>
                        <td>${component.id}</td>
                        <td>${component.description}</td>
                        <td>
                            <button class="btn-edit" onclick="editComponent(${component.id})">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                            <button class="btn-delete" onclick="deleteComponent(${component.id})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('');
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los componentes'
                });
            });
    }
</script>
@endsection
