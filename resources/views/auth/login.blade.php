@extends('layouts.app')

@section('content')
<div class="container" id="login-page">
    <div class="header">
        <!--<img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">-->
        <span>ARDI-MI ♻️</span>
    </div>
    <h1>Gestión de Residuos</h1>

    <div id="form-section">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <input type="email" id="loginEmail" name="email" placeholder="Correo electrónico" required />
            <input type="password" id="loginPassword" name="password" placeholder="Contraseña" required />

            <div class="whatsapp-option">
                <input type="checkbox" id="whatsappNotification" name="whatsapp_notification">
                <label for="whatsappNotification">Recibir notificaciones por WhatsApp</label>
            </div>

            <button type="submit">Ingresar</button>
        </form>

        <div class="login-options">
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            <a href="{{ route('register') }}">¿Todavía no tienes una cuenta? Regístrate</a>
        </div>
    </div>
</div>
@endsection