<div class="logout-button">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-danger">
            <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </button>
    </form>
</div>

<style>
.logout-button {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1050;
}

.logout-button .btn {
    padding: 8px 16px;
    border-radius: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.logout-button .btn i {
    margin-right: 8px;
}
</style>
