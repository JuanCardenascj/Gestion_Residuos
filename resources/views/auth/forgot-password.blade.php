@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Recuperar Contraseña</h1>
        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <input type="email" name="email" required placeholder="Correo electrónico">
            <button type="submit">Enviar enlace</button>
        </form>
    </div>
@endsection