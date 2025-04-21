/*import './bootstrap';*/
import Swal from 'sweetalert2';
import Chart from 'chart.js/auto';


window.Swal = Swal;
window.Chart = Chart;
// 1. Funciones de utilidad
const utils = {
    validateEmail: (email) => {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(email);
    },
    
    validatePassword: (password) => {
      return password.length >= 6;
    },
    
    validatePhone: (phone) => {
      return /^\d{10,15}$/.test(phone);
    },
    
    getWasteTypeName: (type) => {
      const names = {
        organico: 'Orgánico',
        inorganico: 'Inorgánico',
        reciclable: 'Reciclable',
        peligroso: 'Peligroso'
      };
      return names[type] || type;
    },
    
    getStatusName: (status) => {
      const names = {
        pending: 'Pendiente',
        accepted: 'Aceptada',
        completed: 'Completada',
        rejected: 'Rechazada'
      };
      return names[status] || status;
    }
  };
  
  // 2. Funciones para mostrar/ocultar páginas
  const pageManager = {
    showLoginForm: () => {
        document.getElementById('login-page').classList.remove('hidden');
        document.getElementById('password-page').classList.add('hidden');
        document.getElementById('register-page').classList.add('hidden');
        document.getElementById('dashboard-container').classList.add('hidden');
    },
    
    showPasswordRecovery: () => {
        document.getElementById('login-page').classList.add('hidden');
        document.getElementById('password-page').classList.remove('hidden');
    },
    
    showRegisterForm: () => {
        document.getElementById('login-page').classList.add('hidden');
        document.getElementById('register-page').classList.remove('hidden');
    },
    
    showDashboard: () => {
        document.getElementById('login-page').classList.add('hidden');
        document.getElementById('password-page').classList.add('hidden');
        document.getElementById('register-page').classList.add('hidden');
        document.getElementById('dashboard-container').classList.remove('hidden');
    }
};
  
  // 3. Funciones para gráficos
  const chartManager = {
    initPointsChart: (data) => {
      const ctx = document.getElementById('pointsChart')?.getContext('2d');
      if (!ctx) return;
      
      if (window.pointsChart) {
        window.pointsChart.destroy();
      }
  
      window.pointsChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: data.map(r => r.date),
          datasets: [{
            label: 'Puntos obtenidos',
            data: data.map(r => r.points),
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
    },
    
    initCompanyStatsChart: (completed, pending, rejected) => {
      const ctx = document.getElementById('companyStatsChart')?.getContext('2d');
      if (!ctx) return;
      
      if (window.companyStatsChart) {
        window.companyStatsChart.destroy();
      }
  
      window.companyStatsChart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Completadas', 'Pendientes', 'Rechazadas'],
          datasets: [{
            data: [completed, pending, rejected],
            backgroundColor: ['#2e7d32', '#ffc107', '#f44336']
          }]
        }
      });
    },
    
    initAdminRequestsChart: (organic, inorganic, recyclable, hazardous) => {
      const ctx = document.getElementById('adminRequestsChart')?.getContext('2d');
      if (!ctx) return;
      
      if (window.adminRequestsChart) {
        window.adminRequestsChart.destroy();
      }
  
      window.adminRequestsChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Orgánico', 'Inorgánico', 'Reciclable', 'Peligroso'],
          datasets: [{
            label: 'Solicitudes por tipo',
            data: [organic, inorganic, recyclable, hazardous],
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
    }
  };

  // 4. Funciones para gestión de vehículos
const vehicleManager = {
  showAddVehicleForm: (vehicleId = null) => {
      const modal = document.getElementById('vehicle-modal');
      const title = document.getElementById('vehicle-modal-title');

      if (vehicleId) {
          title.textContent = "Editar Vehículo";
          const vehicle = window.vehiclesData.find(v => v.id === vehicleId);
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
  },

  closeVehicleModal: () => {
      document.getElementById('vehicle-modal').classList.add('hidden');
      window.currentEditingVehicleId = null;
  },

  saveVehicle: async () => {
      const plate = document.getElementById('vehicle-plate').value;
      const brand = document.getElementById('vehicle-brand').value;
      const model = document.getElementById('vehicle-model').value;
      const capacity = parseInt(document.getElementById('vehicle-capacity').value);
      const type = document.getElementById('vehicle-type').value;

      if (!plate || !brand || !model || !capacity || isNaN(capacity)) {
          Swal.fire("Error", "Por favor complete todos los campos correctamente", "error");
          return;
      }

      try {
          const response = await fetch(window.currentEditingVehicleId 
              ? `/vehicles/${window.currentEditingVehicleId}`
              : '/vehicles', {
              method: window.currentEditingVehicleId ? 'PUT' : 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({
                  plate,
                  brand,
                  model,
                  capacity,
                  type,
                  _method: window.currentEditingVehicleId ? 'PUT' : 'POST'
              })
          });

          if (response.ok) {
              Swal.fire("Éxito", "Vehículo guardado correctamente", "success");
              window.location.reload();
          } else {
              const error = await response.json();
              Swal.fire("Error", error.message || "Error al guardar el vehículo", "error");
          }
      } catch (error) {
          Swal.fire("Error", "Error de conexión", "error");
      }
  },

  toggleVehicleStatus: async (vehicleId) => {
      try {
          const response = await fetch(`/vehicles/${vehicleId}/toggle-status`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              }
          });

          if (response.ok) {
              Swal.fire("Éxito", "Estado del vehículo actualizado", "success");
              window.location.reload();
          } else {
              const error = await response.json();
              Swal.fire("Error", error.message || "Error al actualizar el estado", "error");
          }
      } catch (error) {
          Swal.fire("Error", "Error de conexión", "error");
      }
  },

  deleteVehicle: async (vehicleId) => {
      const result = await Swal.fire({
          title: "¿Eliminar vehículo?",
          text: "Esta acción no se puede deshacer",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, eliminar",
          cancelButtonText: "Cancelar"
      });

      if (result.isConfirmed) {
          try {
              const response = await fetch(`/vehicles/${vehicleId}`, {
                  method: 'DELETE',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                  }
              });

              if (response.ok) {
                  Swal.fire("Éxito", "Vehículo eliminado correctamente", "success");
                  window.location.reload();
              } else {
                  const error = await response.json();
                  Swal.fire("Error", error.message || "Error al eliminar el vehículo", "error");
              }
          } catch (error) {
              Swal.fire("Error", "Error de conexión", "error");
          }
      }
  }
};

// 5. Funciones para gestión de solicitudes
const requestManager = {
  createCollectionRequest: async () => {
      const date = document.getElementById('collectionDate').value;
      const type = document.getElementById('wasteType').value;
      const weight = parseFloat(document.getElementById('wasteWeight').value);

      if (!date || !type || !weight || weight <= 0) {
          Swal.fire("Error", "Por favor complete todos los campos correctamente", "error");
          return;
      }

      try {
          const response = await fetch('/collection-requests', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({ date, type, weight })
          });

          if (response.ok) {
              Swal.fire("¡Solicitud creada!", "Se ha creado tu solicitud correctamente", "success");
              window.location.reload();
          } else {
              const error = await response.json();
              Swal.fire("Error", error.message || "Error al crear la solicitud", "error");
          }
      } catch (error) {
          Swal.fire("Error", "Error de conexión", "error");
      }
  },

  acceptRequest: async (requestId) => {
      const availableVehicles = window.vehiclesData || [];
      
      if (availableVehicles.length === 0) {
          Swal.fire("Error", "No hay vehículos disponibles", "error");
          return;
      }

      let vehicleOptions = availableVehicles.map(v =>
          `<option value="${v.id}">${v.plate} - ${v.brand} ${v.model} (Capacidad: ${v.capacity}kg)</option>`
      ).join('');

      const { value: result } = await Swal.fire({
          title: "Asignar vehículo",
          html: `
              <p>Seleccione el vehículo para esta recolección:</p>
              <select id="vehicle-assignment" class="swal2-select">
                  ${vehicleOptions}
              </select>
          `,
          showCancelButton: true,
          confirmButtonText: "Aceptar solicitud",
          cancelButtonText: "Cancelar",
          preConfirm: () => {
              return {
                  vehicle_id: parseInt(document.getElementById('vehicle-assignment').value)
              };
          }
      });

      if (result) {
          try {
              const response = await fetch(`/collection-requests/${requestId}/accept`, {
                  method: 'POST',
                  headers: {
                      'Content-Type': 'application/json',
                      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                  },
                  body: JSON.stringify(result)
              });

              if (response.ok) {
                  Swal.fire("Solicitud aceptada", "Has aceptado esta solicitud de recolección", "success");
                  window.location.reload();
              } else {
                  const error = await response.json();
                  Swal.fire("Error", error.message || "Error al aceptar la solicitud", "error");
              }
          } catch (error) {
              Swal.fire("Error", "Error de conexión", "error");
          }
      }
  },

  completeRequest: async () => {
      const requestId = parseInt(document.getElementById('request-select').value);
      const weight = parseFloat(document.getElementById('collected-weight').value);

      if (!requestId || !weight || weight <= 0) {
          Swal.fire("Error", "Por favor complete todos los campos correctamente", "error");
          return;
      }

      try {
          const response = await fetch(`/collection-requests/${requestId}/complete`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
              },
              body: JSON.stringify({ weight })
          });

          if (response.ok) {
              Swal.fire("¡Recolección registrada!", "Has registrado la recolección correctamente", "success");
              window.location.reload();
          } else {
              const error = await response.json();
              Swal.fire("Error", error.message || "Error al registrar la recolección", "error");
          }
      } catch (error) {
          Swal.fire("Error", "Error de conexión", "error");
      }
  }
};
  
  // 6. Event listeners globales
document.addEventListener('DOMContentLoaded', () => {
  // Asignar eventos a los botones de navegación
  const showPasswordRecoveryLink = document.querySelector('a[onclick="showPasswordRecovery()"]');
  const showRegisterFormLink = document.querySelector('a[onclick="showRegisterForm()"]');
  const backToLoginButtons = document.querySelectorAll('.logout-btn[onclick="showLoginForm()"]');
  
  if (showPasswordRecoveryLink) {
      showPasswordRecoveryLink.addEventListener('click', (e) => {
          e.preventDefault();
          pageManager.showPasswordRecovery();
      });
  }
  
  if (showRegisterFormLink) {
      showRegisterFormLink.addEventListener('click', (e) => {
          e.preventDefault();
          pageManager.showRegisterForm();
      });
  }
  
  backToLoginButtons.forEach(button => {
      button.addEventListener('click', (e) => {
          e.preventDefault();
          pageManager.showLoginForm();
      });
  });

  // Asignar eventos de vehículos
  const closeVehicleModalBtn = document.querySelector('.close[onclick="closeVehicleModal()"]');
  const saveVehicleBtn = document.querySelector('button[onclick="saveVehicle()"]');
  
  if (closeVehicleModalBtn) {
      closeVehicleModalBtn.addEventListener('click', (e) => {
          e.preventDefault();
          vehicleManager.closeVehicleModal();
      });
  }
  
  if (saveVehicleBtn) {
      saveVehicleBtn.addEventListener('click', (e) => {
          e.preventDefault();
          vehicleManager.saveVehicle();
      });
  }

  // Asignar eventos de solicitudes
  const createRequestBtn = document.querySelector('button[onclick="createCollectionRequest()"]');
  const completeRequestBtn = document.querySelector('button[onclick="registerCollection()"]');
  
  if (createRequestBtn) {
      createRequestBtn.addEventListener('click', (e) => {
          e.preventDefault();
          requestManager.createCollectionRequest();
      });
  }
  
  if (completeRequestBtn) {
      completeRequestBtn.addEventListener('click', (e) => {
          e.preventDefault();
          requestManager.completeRequest();
      });
  }
  
  // Inicializar gráficos si estamos en el dashboard
  if (document.getElementById('pointsChart')) {
      const requestsData = window.requestsData || [];
      chartManager.initPointsChart(requestsData);
  }
  
  if (document.getElementById('companyStatsChart')) {
      const statsData = window.statsData || { completed: 0, pending: 0, rejected: 0 };
      chartManager.initCompanyStatsChart(
          statsData.completed, 
          statsData.pending, 
          statsData.rejected
      );
  }
  
  if (document.getElementById('adminRequestsChart')) {
      const adminStats = window.adminStats || { organic: 0, inorganic: 0, recyclable: 0, hazardous: 0 };
      chartManager.initAdminRequestsChart(
          adminStats.organic,
          adminStats.inorganic,
          adminStats.recyclable,
          adminStats.hazardous
      );
  }
});
  
 // 7. Hacer funciones disponibles globalmente si es necesario
window.utils = utils;
window.pageManager = pageManager;
window.chartManager = chartManager;
window.vehicleManager = vehicleManager;
window.requestManager = requestManager;

// Funciones globales para llamadas desde HTML
window.showLoginForm = pageManager.showLoginForm;
window.showPasswordRecovery = pageManager.showPasswordRecovery;
window.showRegisterForm = pageManager.showRegisterForm;
window.showAddVehicleForm = vehicleManager.showAddVehicleForm;
window.closeVehicleModal = vehicleManager.closeVehicleModal;
window.saveVehicle = vehicleManager.saveVehicle;
window.toggleVehicleStatus = vehicleManager.toggleVehicleStatus;
window.deleteVehicle = vehicleManager.deleteVehicle;
window.createCollectionRequest = requestManager.createCollectionRequest;
window.acceptRequest = requestManager.acceptRequest;
window.registerCollection = requestManager.completeRequest;