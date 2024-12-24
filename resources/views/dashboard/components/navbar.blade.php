<nav class="navbar">
    <div class="d-flex justify-content-between align-items-center">
        <!-- Botón de menú para móvil -->
        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle">
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
            <!-- Perfil de usuario -->
            <div class="dropdown granjas-btn">
                <button class="btn" type="button" onclick="window.location.href='{{ url('/') }}'">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Granjas</span>
                </button>
            </div>
        </div>
    </div>
</nav>

<style>
.navbar {
    padding: 1rem;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: relative;
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

.granjas-btn {
    position: absolute;
    right: 1rem;
}

.dropdown .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    transition: background-color 0.3s;
}

.dropdown .btn:hover {
    background-color: rgba(0, 0, 0, 0.05);
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        });
    }
    
    // Cerrar sidebar al hacer clic fuera en dispositivos móviles
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }
        }
    });
});
</script>
