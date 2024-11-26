@extends('layouts.main')

@section('title', 'AGROVIDA - Inicio')

@section('content')
<div class="home-container">
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif

    <div class="welcome-section">
        <h1>Bienvenido a AGROVIDA</h1>
        <p>Has iniciado sesión como: {{ Auth::user()->email }}</p>
    </div>

    <div class="action-buttons">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Cerrar Sesión</button>
        </form>
    </div>
</div>

<style>
.home-container {
    max-width: 800px;
    margin: 2rem auto;
    padding: 2rem;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.welcome-section {
    text-align: center;
    margin-bottom: 2rem;
}

.welcome-section h1 {
    color: #3B82F6;
    margin-bottom: 1rem;
}

.action-buttons {
    text-align: center;
}

.alert {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    text-align: center;
}

.alert-success {
    background-color: #d1e7dd;
    color: #0f5132;
    border: 1px solid #badbcc;
}

.btn {
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 1rem;
    transition: background-color 0.3s;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #bb2d3b;
}
</style>
@endsection
