<nav class="navbar">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Botón de menú para móvil -->
        <button class="navbar-toggler d-lg-none">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Buscador -->
        <div class="search-form d-none d-md-block">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar...">
                <button class="btn btn-outline-secondary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Menú derecho -->
        <div class="navbar-right">
            <!-- Notificaciones -->
            <div class="dropdown">
                <button class="btn" type="button">
                    <i class="fas fa-bell"></i>
                    <span class="badge bg-danger">3</span>
                </button>
            </div>

            <!-- Perfil de usuario -->
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button">
                    <img src="{{ asset('dashboard-assets/img/avatar.jpg') }}" alt="User" class="rounded-circle" width="32">
                    <span class="d-none d-md-inline-block ml-2">{{ Auth::user()->name }}</span>
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-user"></i> Mi Perfil
                    </a>
                    <a class="dropdown-item" href="#">
                        <i class="fas fa-cog"></i> Configuración
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 1rem;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navbar-toggler {
    border: none;
    background: none;
    padding: 0.5rem;
}

.search-form {
    max-width: 300px;
}

.search-form .input-group {
    background: #f8f9fa;
    border-radius: 20px;
    overflow: hidden;
}

.search-form input {
    border: none;
    background: none;
    padding: 0.5rem 1rem;
}

.search-form button {
    border: none;
    background: none;
    color: #6c757d;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.navbar-right .btn {
    position: relative;
    padding: 0.5rem;
}

.navbar-right .badge {
    position: absolute;
    top: 0;
    right: 0;
    transform: translate(25%, -25%);
}

.dropdown-menu {
    min-width: 200px;
    padding: 0.5rem;
    border: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.dropdown-item {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    border-radius: 4px;
}

.dropdown-item:hover {
    background: #f8f9fa;
}

.dropdown-divider {
    margin: 0.5rem 0;
}
</style>
