<?php require_once '../layouts/header.php'; ?>

<h1>Bienvenido, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>

<div id="user-section">
  <h2><i class="fas fa-trash-alt"></i> Solicitar Recolección</h2>
  <form method="POST" action="/user/request">
    <input type="date" name="collection_date" required>
    <select name="waste_type" required>
      <option value="">Seleccione tipo de residuo</option>
      <?php foreach (WASTE_TYPES as $key => $value): ?>
        <option value="<?= $key ?>"><?= $value ?></option>
      <?php endforeach; ?>
    </select>
    <input type="number" name="waste_weight" placeholder="Peso (kg)" min="0.1" step="0.1" required>
    <button type="submit">Solicitar Recolección</button>
  </form>
  
  <div class="section">
    <h3><i class="fas fa-coins"></i> Puntos Acumulados</h3>
    <p>Total de puntos: <strong><?= $points ?></strong></p>
    <div class="chart-container">
      <canvas id="pointsChart"></canvas>
    </div>
  </div>
</div>

<script>
  // Inicializar gráfico de puntos
  const userRequests = <?= json_encode($requests) ?>;
  createUserPointsChart(userRequests);
</script>

<?php require_once '../layouts/footer.php'; ?>