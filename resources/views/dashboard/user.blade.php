@extends('layouts.app')

@section('content')
<div class="container" id="dashboard-container">
    <div class="header">
        <!--<img src="{{ asset('images/logo.png') }}" alt="logo" class="logo">-->
        <span>ARDI-MI ♻️ - Usuario</span>
    </div>
    <h1 id="welcome-message">Bienvenido, {{ Auth::user()->name }}</h1>

    <div id="user-section" class="section">
        <h2><i class="fas fa-trash-alt"></i> Solicitar Recolección</h2>
        <form action="{{ route('collection-requests.store') }}" method="POST">
            @csrf
            <input type="date" id="collectionDate" name="date" required />
            <select id="wasteType" name="type" required>
                <option value="">Seleccione tipo de residuo</option>
                <option value="organico">Orgánicos 🍂</option>
                <option value="inorganico">Inorganicos 🏗️</option>
                <option value="reciclable">Reciclables ♻️</option>
                <option value="peligroso">Peligrosos ☣️</option>
            </select>
            <input type="number" id="wasteWeight" name="weight" placeholder="Peso (kg)" min="0.1" step="0.1" required />
            <button type="submit">Solicitar Recolección</button>
        </form>

        <div class="section">
            <h3><i class="fas fa-coins"></i> Puntos Acumulados</h3>
            <p>Total de puntos: <strong id="total-points">{{ Auth::user()->points }}</strong></p>
            <div class="chart-container">
                <canvas id="pointsChart"></canvas>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Inicializar gráfico
    const ctx = document.getElementById('pointsChart').getContext('2d');
    const requests = @json($requests);
    
    window.pointsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: requests.map(r => r.date),
            datasets: [{
                label: 'Puntos obtenidos',
                data: requests.map(r => r.points),
                backgroundColor: '#2e7d32'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush