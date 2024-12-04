<div class="sidebar">
    <div class="sidebar-header">
        <img src="{{ asset('dashboard-assets/img/logo.png') }}" alt="Logo">
    </div>
    
    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
            </li>
            <!-- Widgets -->
            <li>
                <a href="{{ route('widgets') }}" class="{{ request()->routeIs('widgets') ? 'active' : '' }}">
                    <i class="fas fa-th"></i>
                    <span>Widgets</span>
                </a>
            </li>
            <!-- Basic UI -->
            <li>
                <a href="{{ route('ui') }}" class="{{ request()->routeIs('ui') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i>
                    <span>UI Elements</span>
                </a>
            </li>
            <!-- Forms -->
            <li>
                <a href="{{ route('forms') }}" class="{{ request()->routeIs('forms') ? 'active' : '' }}">
                    <i class="fas fa-edit"></i>
                    <span>Forms</span>
                </a>
            </li>
            <!-- Charts -->
            <li>
                <a href="{{ route('charts') }}" class="{{ request()->routeIs('charts') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Charts</span>
                </a>
            </li>
            <!-- Tables -->
            <li>
                <a href="{{ route('tables') }}" class="{{ request()->routeIs('tables') ? 'active' : '' }}">
                    <i class="fas fa-table"></i>
                    <span>Tables</span>
                </a>
            </li>
        </ul>
    </nav>
</div>
