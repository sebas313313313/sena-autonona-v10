<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-container">
            <div class="logo-title-wrapper">
                <div class="spiral-sun-logo">
                    <svg viewBox="0 0 60 60" class="spiral-svg">
                        <!-- Espiral central -->
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
            <li>
                <a href="{{ route('dashboard.home') }}" class="{{ request()->routeIs('dashboard.home') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Datos</span>
                </a>
            </li>
            
            @if(session('farm_role') != 'operario')
            <li>
                <a href="{{ route('widgets') }}" class="{{ request()->routeIs('widgets') ? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    <span>Granjas</span>
                </a>
            </li>
            @endif
            
            <li>
                <a href="{{ route('ui') }}" class="{{ request()->routeIs('ui') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>
                    <span>Tareas</span>
                </a>
            </li>
            
            @if(session('farm_role') != 'operario')
            <li>
                <a href="{{ route('forms') }}" class="{{ request()->routeIs('forms') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>
            
            <li>
                <a href="{{ route('tables') }}" class="{{ request()->routeIs('tables') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Estadísticas</span>
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

.sidebar-title {
    font-size: 1.2rem;
    margin: 0;
    color: #22c55e;
}

.spiral-path {
    fill: none;
    stroke: #22c55e;
    stroke-width: 2;
    animation: draw-spiral 3s linear forwards, rotate-spiral 20s linear infinite;
    stroke-dasharray: 400;
    stroke-dashoffset: 400;
    transform-origin: 30px 30px;
}

@keyframes draw-spiral {
    to {
        stroke-dashoffset: 0;
    }
}

@keyframes rotate-spiral {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
