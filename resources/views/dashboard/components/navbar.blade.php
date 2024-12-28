<nav class="navbar">
    <div class="d-flex justify-content-between align-items-center w-100">
        <!-- Botón de menú para móvil -->
        <button class="navbar-toggler" type="button" id="sidebarToggle" aria-label="Toggle navigation">
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

        <h4 class="mb-0 text-center flex-grow-1 mx-3">
            <i class="fas fa-warehouse text-success me-2"></i>{{ $farm->name ?? 'Sin Granja Seleccionada' }}
        </h4>

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
}

.search-form {
    width: 250px;
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

.navbar-right .btn {
    padding: 0.5rem 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

h4.flex-grow-1 {
    font-size: 1.25rem;
    color: #2c3e50;
    font-weight: 500;
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
