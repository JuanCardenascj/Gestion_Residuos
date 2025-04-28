<?php if (isset($_SESSION['user'])): ?>
    <button class="logout-btn" onclick="window.location.href='/logout'">
      <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
    </button>
  <?php endif; ?>
</div>

<script src="/assets/js/main.js"></script>
<script src="/assets/js/charts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>