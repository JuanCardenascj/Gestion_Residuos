<?php require_once '../layouts/header.php'; ?>

<h1>Panel de Administración</h1>

<div id="admin-section">
  <h2><i class="fas fa-tachometer-alt"></i> Estadísticas</h2>
  <form method="POST" action="/admin/filter" id="filter-form">
    <div class="filter-section">
      <input type="date" name="start_date" id="admin-start-date">
      <input type="date" name="end_date" id="admin-end-date">
      <select name="type" id="admin-filter-type">
        <option value="all">Todos los tipos</option>
        <?php foreach (WASTE_TYPES as $key => $value): ?>
          <option value="<?= $key ?>"><?= $value ?></option>
        <?php endforeach; ?>
      </select>
      <input type="number" name="min_weight" placeholder="Peso mínimo (kg)" min="0" step="0.1">
      <input type="number" name="max_weight" placeholder="Peso máximo (kg)" min="0" step="0.1">
      <button type="submit">Filtrar</button>
    </div>
  </form>
  
  <div class="chart-container">
    <canvas id="adminRequestsChart"></canvas>
  </div>
  
  <div class="section">
    <h3><i class="fas fa-users"></i> Usuarios Registrados</h3>
    <button onclick="window.location.href='/admin/users/add'">
      <i class="fas fa-plus"></i> Agregar Usuario
    </button>
    <ul id="users-list">
      <?php foreach ($users as $user): ?>
        <li>
          <div>
            <strong><?= htmlspecialchars($user['name']) ?></strong> (<?= $user['email'] ?>)<br>
            Teléfono: <?= $user['phone'] ?> | Puntos: <?= $user['points'] ?> | 
            Estado: <span class="status-badge status-<?= $user['status'] ?>">
              <?= $user['status'] === 'active' ? 'Activo' : 'Inactivo' ?>
            </span>
          </div>
          <div>
            <a href="/admin/users/edit/<?= $user['id'] ?>">Editar</a>
            <a href="/admin/users/toggle/<?= $user['id'] ?>">
              <?= $user['status'] === 'active' ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="section">
    <h3><i class="fas fa-building"></i> Empresas Recolectoras</h3>
    <button onclick="window.location.href='/admin/companies/add'">
      <i class="fas fa-plus"></i> Agregar Empresa
    </button>
    <ul id="companies-list">
      <?php foreach ($companies as $company): ?>
        <li>
          <div>
            <strong><?= htmlspecialchars($company['name']) ?></strong> (<?= $company['email'] ?>)<br>
            Teléfono: <?= $company['phone'] ?> | 
            Estado: <span class="status-badge status-<?= $company['status'] ?>">
              <?= $company['status'] === 'active' ? 'Activa' : 'Inactiva' ?>
            </span>
          </div>
          <div>
            <a href="/admin/companies/edit/<?= $company['id'] ?>">Editar</a>
            <a href="/admin/companies/toggle/<?= $company['id'] ?>">
              <?= $company['status'] === 'active' ? 'Desactivar' : 'Activar' ?>
            </a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
  
  <div class="filter-section">
    <button onclick="window.location.href='/admin/export/users/pdf'">Exportar Usuarios (PDF)</button>
    <button onclick="window.location.href='/admin/export/requests/excel'">Exportar Solicitudes (Excel)</button>
  </div>
</div>

<script>
  // Inicializar gráfico de estadísticas
  const requestsData = <?= json_encode($requests) ?>;
  updateAdminStats(requestsData);
</script>

<?php require_once '../layouts/footer.php'; ?>