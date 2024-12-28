<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-title-wrapper">
                <div class="spiral-sun-logo">
                    <svg viewBox="0 0 60 60" class="spiral-svg">
                        <path class="spiral-path" d="
                            M 30 15
                            A 15 15 0 0 1 45 30
                            A 15 15 0 0 1 30 45
                            A 15 15 0 0 1 15 30
                            A 15 15 0 0 1 30 15
                            
                            A 12 12 0 0 0 42 30
                            A 12 12 0 0 0 30 42
                            A 12 12 0 0 0 18 30
                            A 12 12 0 0 0 30 18" />
                    </svg>
                </div>
                <h3 class="sidebar-title">Agrovida</h3>
            </div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            {{-- Datos siempre visible para todos --}}
            <li class="nav-item">
                <a href="{{ route('dashboard.home', ['farm_id' => session('current_farm_id')]) }}" class="nav-link {{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Datos</span>
                </a>
            </li>
            
            {{-- Tareas visible para todos dentro de una granja --}}
            @if(session('current_farm_id'))
                <li class="nav-item">
                    <a href="{{ route('tasks.index', ['farm_id' => session('current_farm_id')]) }}" class="nav-link {{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>Tareas</span>
                    </a>
                </li>
            @endif
            
            {{-- Las siguientes opciones solo son visibles para administradores --}}
            @if(session('farm_role') === 'admin')
                {{-- Granjas siempre visible para administradores --}}
                <li class="nav-item">
                    <a href="{{ route('sensores.index', ['farm_id' => session('current_farm_id')]) }}" class="nav-link {{ request()->routeIs('sensores.index') ? 'active' : '' }}">
                        <i class="fas fa-microchip"></i>
                        <span>Sensores</span>
                    </a>
                </li>
                
                {{-- Usuarios y Estadísticas --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard.users', ['farm_id' => session('current_farm_id')]) }}" class="nav-link {{ request()->routeIs('dashboard.users') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
</div>

<style>
.logo-container {
    text-align: center;
    margin-bottom: 1rem;
}

.logo-title-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.spiral-sun-logo {
    width: 30px;
    height: 30px;
    position: relative;
}

.spiral-svg {
    width: 100%;
    height: 100%;
}

.spiral-path {
    fill: none;
    stroke: #22c55e;
    stroke-width: 1.5;
}

.sidebar-title {
    font-size: 1.2rem;
    margin: 0;
    color: #22c55e;
}

.sidebar {
    background-color: #ffffff;
    border-right: 1px solid #e5e7eb;
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    overflow-y: auto;
    padding: 1rem;
}

.sidebar-nav ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-nav li {
    margin-bottom: 0.5rem;
}

.sidebar-nav a {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #4b5563;
    text-decoration: none;
    border-radius: 0.375rem;
    transition: all 0.2s;
}

.sidebar-nav a:hover {
    background-color: #f3f4f6;
    color: #22c55e;
}

.sidebar-nav a.active {
    background-color: #22c55e;
    color: #ffffff;
}

.sidebar-nav i {
    width: 20px;
    margin-right: 0.75rem;
}
</style>
