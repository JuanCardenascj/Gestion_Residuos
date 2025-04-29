@extends('layouts.app')

@section('content')
<h1>Bienvenido {{ $user->name }} (Usuario)</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5>Solicitar Recolección</h5>

        <form method="POST" action="{{ route('collection.request') }}">
            @csrf
            <div class="mb-3">
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <select name="type" class="form-control" required>
                    <option value="">Seleccione tipo de residuo</option>
                    <option value="organico">Orgánico 🍂</option>
                    <option value="inorganico">Inorgánico 🏗️</option>
                    <option value="reciclable">Reciclable ♻️</option>
                    <option value="peligroso">Peligroso ☣️</option>
                </select>
            </div>
            <div class="mb-3">
                <input type="number" name="weight" class="form-control" placeholder="Peso (kg)" min="0.1" step="0.1" required>
            </div>
            <button type="submit" class="btn btn-success">Solicitar</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h5>Puntos acumulados: {{ $user->points }}</h5>
    </div>
</div>
@endsection
