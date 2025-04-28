<?php require_once '../layouts/header.php'; ?>

<h1>Bienvenido, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>

<div id="company-section">
  <h2><i class="fas fa-truck"></i> Solicitudes Pendientes</h2>
  <?php if (empty($pendingRequests)): ?>
    <p>No hay solicitudes pendientes</p>
  <?php else: ?>
    <ul id="pending-requests-list">
      <?php foreach ($pendingRequests as $request): ?>
        <li>
          <div>
            <strong><?= htmlspecialchars($request['user_name']) ?></strong><br>
            Fecha: <?= $request['date'] ?> | 
            Tipo: <?= WASTE_TYPES[$request['type']] ?> | 
            Peso: <?= $request['weight'] ?>kg
          </div>
          <form method="POST" action="/company/accept">
            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
            <button type="submit">Aceptar</button>
          </form>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
  
  <h3><i class="fas fa-weight-hanging"></i> Registrar Recolección</h3>
  <form method="POST" action="/company/complete">
    <select name="request_id" required>
      <option value="">Seleccione una solicitud</option>
      <?php foreach ($acceptedRequests as $request): ?>
        <option value="<?= $request['id'] ?>">
          <?= $request['date'] ?> - <?= WASTE_TYPES[$request['type']] ?>
        </option>
      <?php endforeach; ?>
    </select>
    <input type="number" name="collected_weight" placeholder="Peso recolectado (kg)" min="0.1" step="0.1" required>
    <button type="submit">Registrar</button>
  </form>
  
  <div class="section">
    <h3><i class="fas fa-truck-moving"></i> Gestión de Vehículos Recolectores</h3>
    <button onclick="window.location.href='/company/vehicles/add'">
      <i class="fas fa-plus"></i> Añadir Vehículo
    </button>
    <div id="vehicles-list">
      <?php foreach ($vehicles as $vehicle): ?>
        <div class="vehicle-card">
          <h4><?= $vehicle['brand'] ?> <?= $vehicle['model'] ?> 
            <span class="status-badge status-<?= $vehicle['status'] ?>">
              <?= $vehicle['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
            </span>
          </h4>
          <div class="vehicle-property"><strong>Placa:</strong> <?= $vehicle['plate'] ?></div>
          <div class="vehicle-property"><strong>Capacidad:</strong> <?= $vehicle['capacity'] ?> kg</div>
          <div class="vehicle-property"><strong>Tipo:</strong> <?= VEHICLE_TYPES[$vehicle['type']] ?></div>
          <div class="vehicle-actions">
            <a href="/company/vehicles/edit/<?= $vehicle['id'] ?>">Editar</a>
            <a href="/company/vehicles/toggle/<?= $vehicle['id'] ?>">
              <?= $vehicle['status'] === 'active' ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<?php require_once '../layouts/footer.php'; ?>