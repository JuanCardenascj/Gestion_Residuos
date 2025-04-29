@extends('layouts.app')

@section('content')
<h1>Panel de Administración (Admin)</h1>

<div class="card">
    <div class="card-body">
        <h5>Desde aquí el administrador podrá ver reportes generales, exportar datos, etc. (pendiente de construir)</h5>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-4">
            <div class="card-body">
                <h5 class="card-title">Usuarios registrados</h5>
                <p class="card-text display-4">{{ \App\Models\User::where('role', 'user')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-success mb-4">
            <div class="card-body">
                <h5 class="card-title">Empresas recolectoras</h5>
                <p class="card-text display-4">{{ \App\Models\User::where('role', 'company')->count() }}</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card text-white bg-warning mb-4">
            <div class="card-body">
                <h5 class="card-title">Solicitudes registradas</h5>
                <p class="card-text display-4">{{ \App\Models\CollectionRequest::count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
