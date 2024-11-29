@extends('layouts.main')

@section('title', 'AGROVIDA - Registro')

@section('content')
<div class="register-page">
    <img src="{{ asset('images/pez.png') }}" alt="Pez decorativo" class="decorative-image">
    <div class="register-box">
        <div class="logo-container">
            <div class="logo-title-wrapper">
                <svg class="sun-logo" width="60" height="60" viewBox="0 0 60 60">
                    <circle cx="30" cy="30" r="15" fill="#22c55e" />
                    @for ($i = 0; $i < 8; $i++)
                        <line 
                            x1="{{ 30 + cos($i * pi() / 4) * 15 }}"
                            y1="{{ 30 + sin($i * pi() / 4) * 15 }}"
                            x2="{{ 30 + cos($i * pi() / 4) * 25 }}"
                            y2="{{ 30 + sin($i * pi() / 4) * 25 }}"
                            stroke="#22c55e"
                            stroke-width="4"
                            stroke-linecap="round"
                        />
                    @endfor
                </svg>
                <h1 class="logo-text">AGROVIDA</h1>
            </div>
            <h2 class="register-title">Registro</h2>
        </div>

        <form method="POST" action="{{ route('register.submit') }}" class="register-form">
            @csrf
            <!-- Información básica del usuario -->
            <div class="form-group">
                <input type="email" 
                       class="form-input @error('email') is-invalid @enderror" 
                       name="email" 
                       value="{{ old('email') }}" 
                       placeholder="Correo electrónico"
                       required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" 
                       class="form-input @error('password') is-invalid @enderror" 
                       name="password" 
                       placeholder="Contraseña"
                       required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="password" 
                       class="form-input"
                       name="password_confirmation" 
                       placeholder="Confirmar contraseña"
                       required>
            </div>

            <!-- Información detallada del usuario -->
            <div class="form-group">
                <select class="form-input @error('identification_type_id') is-invalid @enderror" 
                        name="identification_type_id" 
                        required>
                    <option value="">Tipo de identificación</option>
                    @foreach($identificationTypes as $type)
                        <option value="{{ $type->id }}">{{ $type->description }}</option>
                    @endforeach
                </select>
                @error('identification_type_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-input @error('identification') is-invalid @enderror" 
                       name="identification" 
                       value="{{ old('identification') }}" 
                       placeholder="Número de identificación"
                       maxlength="15"
                       required>
                @error('identification')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-input @error('name') is-invalid @enderror" 
                       name="name" 
                       value="{{ old('name') }}" 
                       placeholder="Nombres"
                       maxlength="50"
                       required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-input @error('last_name') is-invalid @enderror" 
                       name="last_name" 
                       value="{{ old('last_name') }}" 
                       placeholder="Apellidos"
                       maxlength="50"
                       required>
                @error('last_name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="date" 
                       class="form-input @error('birth_date') is-invalid @enderror" 
                       name="birth_date" 
                       value="{{ old('birth_date') }}" 
                       required>
                @error('birth_date')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <select class="form-input @error('municipality_id') is-invalid @enderror" 
                        name="municipality_id" 
                        required>
                    <option value="">Municipio</option>
                    @foreach($municipalities as $municipality)
                        <option value="{{ $municipality->id }}">{{ $municipality->name }}</option>
                    @endforeach
                </select>
                @error('municipality_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="text" 
                       class="form-input @error('direction') is-invalid @enderror" 
                       name="direction" 
                       value="{{ old('direction') }}" 
                       placeholder="Dirección"
                       maxlength="50"
                       required>
                @error('direction')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <input type="tel" 
                       class="form-input @error('contact_number') is-invalid @enderror" 
                       name="contact_number" 
                       value="{{ old('contact_number') }}" 
                       placeholder="Teléfono de contacto"
                       maxlength="10"
                       pattern="[0-9]{10}"
                       required>
                @error('contact_number')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="register-button">
                Registrar
            </button>

            <div class="login-link">
                ¿Ya tienes cuenta? 
                <a href="{{ route('login') }}" class="login-text">Inicia sesión</a>
            </div>
        </form>
    </div>
</div>
<style>
.logo-container {
    text-align: center;
    margin-bottom: 2rem;
}

.logo-title-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.logo-text {
    font-size: 2rem;
    font-weight: bold;
    color: #000000;
    margin: 0;
}

.register-title {
    font-size: 1.5rem;
    color: #000000;
    margin: 0;
}

.sun-logo {
    width: 60px;
    height: 60px;
}

.decorative-image {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: -1;
    opacity: 1;
}
</style>
@endsection
