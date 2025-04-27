<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';

if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

$user = $_SESSION['user'];
$pageTitle = "Dashboard - " . ($user['role'] === 'company' ? 'Empresa Recolectora' : 'Usuario');

// Obtener datos del dashboard según el rol
if ($user['role'] === 'user') {
    // Obtener solicitudes del usuario
    $stmt = $pdo->prepare("SELECT * FROM collection_requests WHERE user_id = ? ORDER BY date DESC");
    $stmt->execute([$user['id']]);
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcular puntos totales
    $totalPoints = array_reduce($requests, function($carry, $request) {
        return $carry + $request['points'];
    }, 0);
} elseif ($user['role'] === 'company') {
    // Obtener solicitudes pendientes para la empresa
    $stmt = $pdo->prepare("SELECT cr.*, u.name as user_name 
                          FROM collection_requests cr
                          JOIN users u ON cr.user_id = u.id
                          WHERE cr.status = 'pending' 
                          ORDER BY cr.date DESC");
    $stmt->execute();
    $pendingRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Obtener vehículos de la empresa
    $stmt = $pdo->prepare("SELECT * FROM vehicles WHERE company_id = ?");
    $stmt->execute([$user['id']]);
    $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

require_once '../includes/header.php';
?>

<div class="container" id="dashboard-container">
  <div class="header">
    <img src="../assets/images/logo.png" alt="logo" class="logo">
    <span><?= APP_NAME ?> - <span id="user-role-display">
      <?= $user['role'] === 'user' ? 'Usuario' : 'Empresa Recolectora' ?>
    </span></span>
  </div>
  <h1 id="welcome-message">Bienvenido, <?= htmlspecialchars($user['name']) ?></h1>

  <?php if ($user['role'] === 'user'): ?>
    <!-- Sección Usuario -->
    <div id="user-section">
      <h2><i class="fas fa-trash-alt"></i> Solicitar Recolección</h2>
      <form method="POST" action="create_request.php">
        <input type="date" name="collection_date" required />
        <select name="waste_type" required>
          <option value="">Seleccione tipo de residuo</option>
          <option value="organico">Orgánico 🍂</option>
          <option value="inorganico">Inorgánico 🏗️</option>
          <option value="reciclable">Reciclable ♻️</option>
          <option value="peligroso">Peligroso ☣️</option>
        </select>
        <input type="number" name="waste_weight" placeholder="Peso (kg)" min="0.1" step="0.1" required />
        <button type="submit">Solicitar Recolección</button>
      </form>
      
      <div class="section">
        <h3><i class="fas fa-coins"></i> Puntos Acumulados</h3>
        <p>Total de puntos: <strong id="total-points"><?= $totalPoints ?></strong></p>
        <div class="chart-container">
          <canvas id="pointsChart"></canvas>
        </div>
      </div>
    </div>

  <?php elseif ($user['role'] === 'company'): ?>
    <!-- Sección Empresa -->
    <div id="company-section">
      <h2><i class="fas fa-truck"></i> Solicitudes Pendientes</h2>
      <ul id="pending-requests-list">
        <?php if (empty($pendingRequests)): ?>
          <li>No hay solicitudes pendientes</li>
        <?php else: ?>
          <?php foreach ($pendingRequests as $request): ?>
            <li>
              <div>
                <strong><?= htmlspecialchars($request['user_name']) ?></strong><br>
                Fecha: <?= $request['date'] ?> | 
                Tipo: <?= getWasteTypeName($request['waste_type']) ?> | 
                Peso: <?= $request['weight'] ?>kg
              </div>
              <form method="POST" action="accept_request.php" style="display: inline;">
                <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
                <button type="submit">Aceptar</button>
              </form>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
      
      <h3><i class="fas fa-weight-hanging"></i> Registrar Recolección</h3>
      <form method="POST" action="register_collection.php" class="filter-section">
        <select name="request_id" required>
          <option value="">Seleccione una solicitud</option>
          <?php 
          $stmt = $pdo->prepare("SELECT cr.*, u.name as user_name 
                                FROM collection_requests cr
                                JOIN users u ON cr.user_id = u.id
                                WHERE cr.company_id = ? AND cr.status = 'accepted'");
          $stmt->execute([$user['id']]);
          $acceptedRequests = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
          foreach ($acceptedRequests as $request): ?>
            <option value="<?= $request['id'] ?>">
              <?= htmlspecialchars($request['user_name']) ?> - <?= $request['date'] ?> - 
              <?= getWasteTypeName($request['waste_type']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="number" name="collected_weight" placeholder="Peso recolectado (kg)" min="0.1" step="0.1" required>
        <button type="submit">Registrar</button>
      </form>
      
      <div class="section">
        <h3><i class="fas fa-truck-moving"></i> Gestión de Vehículos Recolectores</h3>
        <button onclick="window.location.href='add_vehicle.php'">
          <i class="fas fa-plus"></i> Añadir Vehículo
        </button>
        <div id="vehicles-list">
          <?php if (empty($vehicles)): ?>
            <p>No hay vehículos registrados</p>
          <?php else: ?>
            <?php foreach ($vehicles as $vehicle): ?>
              <div class="vehicle-card">
                <h4><?= htmlspecialchars($vehicle['brand']) ?> <?= htmlspecialchars($vehicle['model']) ?> 
                  <span class="status-badge status-<?= $vehicle['status'] ?>">
                    <?= $vehicle['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
                  </span>
                </h4>
                <div class="vehicle-property"><strong>Placa:</strong> <?= $vehicle['plate'] ?></div>
                <div class="vehicle-property"><strong>Capacidad:</strong> <?= $vehicle['capacity'] ?> kg</div>
                <div class="vehicle-property"><strong>Tipo:</strong> <?= $vehicle['type'] ?></div>
                <div class="vehicle-actions">
                  <a href="edit_vehicle.php?id=<?= $vehicle['id'] ?>" class="btn">Editar</a>
                  <form method="POST" action="toggle_vehicle_status.php" style="display: inline;">
                    <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                    <button type="submit">
                      <?= $vehicle['status'] === 'active' ? 'Desactivar' : 'Activar' ?>
                    </button>
                  </form>
                  <form method="POST" action="delete_vehicle.php" style="display: inline;">
                    <input type="hidden" name="vehicle_id" value="<?= $vehicle['id'] ?>">
                    <button type="submit" class="btn-danger">Eliminar</button>
                  </form>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <form method="POST" action="../logout.php" style="margin-top: 20px;">
    <button type="submit" class="logout-btn">
      <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </button>
  </form>
</div>

<?php 
// Incluir gráficos solo si es necesario
if ($user['role'] === 'user'): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Datos para el gráfico de puntos
  const requestsData = <?= json_encode($requests) ?>;
  
  // Configurar gráfico
  const ctx = document.getElementById('pointsChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: requestsData.map(r => r.date),
      datasets: [{
        label: 'Puntos obtenidos',
        data: requestsData.map(r => r.points),
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
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>