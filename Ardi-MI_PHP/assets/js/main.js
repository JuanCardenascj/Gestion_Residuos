// Funciones para mostrar/ocultar páginas
function showLoginForm() {
  document.getElementById('login-page').classList.remove('hidden');
  document.getElementById('password-page').classList.add('hidden');
  document.getElementById('register-page').classList.add('hidden');
  document.getElementById('dashboard-container').classList.add('hidden');
}

function showPasswordRecovery() {
  document.getElementById('login-page').classList.add('hidden');
  document.getElementById('password-page').classList.remove('hidden');
}

function showRegisterForm() {
  document.getElementById('login-page').classList.add('hidden');
  document.getElementById('register-page').classList.remove('hidden');
}

function showDashboard() {
  document.getElementById('login-page').classList.add('hidden');
  document.getElementById('password-page').classList.add('hidden');
  document.getElementById('register-page').classList.add('hidden');
  document.getElementById('dashboard-container').classList.remove('hidden');
  updateDashboard();
}

// Funciones para gestión de vehículos
function showAddVehicleForm(vehicleId = null) {
  currentEditingVehicleId = vehicleId;
  const modal = document.getElementById('vehicle-modal');
  const title = document.getElementById('vehicle-modal-title');
  
  if (vehicleId) {
    title.textContent = "Editar Vehículo";
    const vehicle = vehicles.find(v => v.id === vehicleId);
    if (vehicle) {
      document.getElementById('vehicle-plate').value = vehicle.plate;
      document.getElementById('vehicle-brand').value = vehicle.brand;
      document.getElementById('vehicle-model').value = vehicle.model;
      document.getElementById('vehicle-capacity').value = vehicle.capacity;
      document.getElementById('vehicle-type').value = vehicle.type;
    }
  } else {
    title.textContent = "Añadir Nuevo Vehículo";
    ['plate', 'brand', 'model', 'capacity'].forEach(field => {
      document.getElementById(`vehicle-${field}`).value = '';
    });
    document.getElementById('vehicle-type').value = 'compacto';
  }
  
  modal.classList.remove('hidden');
}

function closeVehicleModal() {
  document.getElementById('vehicle-modal').classList.add('hidden');
  currentEditingVehicleId = null;
}

// Funciones de ayuda
function getWasteTypeName(type) {
  const names = {
    organico: 'Orgánico',
    inorganico: 'Inorgánico',
    reciclable: 'Reciclable',
    peligroso: 'Peligroso'
  };
  return names[type] || type;
}

function getStatusName(status) {
  const names = {
    pending: 'Pendiente',
    accepted: 'Aceptada',
    completed: 'Completada',
    rejected: 'Rechazada'
  };
  return names[status] || status;
}