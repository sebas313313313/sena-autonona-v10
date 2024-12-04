<div class="sidebar">
    <div class="position-sticky pt-3">
        <div class="px-3 mb-4">
            <h4>SENA Dashboard</h4>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                    <i class="bi bi-tv"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="/users">
                    <i class="bi bi-people"></i>
                    Usuarios
                </a>
            </li>
        </ul>
    </div>
</div>

<style>
.sidebar {
    width: 240px;
}
.nav-link {
    color: #333;
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.nav-link:hover, .nav-link.active {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
}
.nav-link i {
    font-size: 1.2rem;
}
</style>
