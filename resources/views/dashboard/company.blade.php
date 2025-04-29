@extends('layouts.app')

@section('content')
<h1>Bienvenido {{ $user->name }} (Empresa Recolectora)</h1>

<div class="card mb-4">
    <div class="card-body">
        <h5>Registrar Vehículo</h5>

        <form method="POST" action="{{ route('vehicle.store') }}">
            @csrf
            <div class="mb-3">
                <input type="text" name="plate" class="form-control" placeholder="Placa" required>
            </div>
            <div class="mb-3">
                <input type="text" name="brand" class="form-control" placeholder="Marca" required>
            </div>
            <div class="mb-3">
                <input type="text" name="model" class="form-control" placeholder="Modelo" required>
            </div>
            <div class="mb-3">
                <input type="number" name="capacity" class="form-control" placeholder="Capacidad (kg)" min="100" required>
            </div>
            <div class="mb-3">
                <select name="type" class="form-control" required>
                    <option value="compacto">Compacto</option>
                    <option value="mediano">Mediano</option>
                    <option value="grande">Grande</option>
                    <option value="especial">Especial (Peligrosos)</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Añadir Vehículo</button>
        </form>
    </div>
</div>
@endsection
