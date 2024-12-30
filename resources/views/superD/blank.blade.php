@extends('layouts.main')

@section('title', 'Login SuperD')

@section('content')
<style>
    .login-superD {
        min-height: 100vh;
        background: linear-gradient(135deg, #1a5f7a 0%, #0d2f3f 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
        padding: 3rem 2rem;
        width: 90%;
        max-width: 380px;
        text-align: center;
    }
    
    .login-header {
        margin-bottom: 2.5rem;
    }
    
    .login-header h4 {
        color: #1a5f7a;
        font-size: 2.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .login-header p {
        color: #666;
        font-size: 1rem;
    }
    
    .login-form {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
        text-align: left;
    }
    
    .form-control {
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        padding: 12px 15px 12px 45px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 0.2rem rgba(34, 197, 94, 0.25);
    }
    
    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #1a5f7a;
        font-size: 1.1rem;
    }
    
    .btn-login {
        width: 100%;
        background: #22c55e;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }
    
    .btn-login:hover {
        background: #1ea550;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
    }
</style>

<div class="login-superD">
    <div class="login-card">
        <div class="login-header">
            <h4>SuperA Login</h4>
            <p>Panel de Administración Especial</p>
        </div>
        
        <form action="{{ route('superD.login') }}" method="POST" class="login-form">
            @csrf
            
            <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" 
                       class="form-control" 
                       id="email" 
                       name="email" 
                       placeholder="Correo Electrónico"
                       required>
            </div>

            <div class="input-group">
                <i class="fas fa-lock input-icon"></i>
                <input type="password" 
                       class="form-control" 
                       id="password" 
                       name="password" 
                       placeholder="Contraseña"
                       required>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Iniciar Sesión
            </button>
        </form>
    </div>
</div>
@endsection
