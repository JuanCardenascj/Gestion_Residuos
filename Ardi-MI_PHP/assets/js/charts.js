// Funciones para crear gráficos
function createUserPointsChart(userRequests) {
    const ctx = document.getElementById('pointsChart').getContext('2d');
    
    if (window.pointsChart) {
      window.pointsChart.destroy();
    }
    
    window.pointsChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: userRequests.map(r => r.date),
        datasets: [{
          label: 'Puntos obtenidos',
          data: userRequests.map(r => r.points),
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
  
  function createCompanyStatsChart(companyRequests) {
    const ctx = document.getElementById('companyStatsChart').getContext('2d');
    
    return new Chart(ctx, {
      type: 'pie',
      data: {
        labels: ['Completadas', 'Pendientes', 'Rechazadas'],
        datasets: [{
          data: [
            companyRequests.filter(r => r.status === 'completed').length,
            companyRequests.filter(r => r.status === 'accepted').length,
            companyRequests.filter(r => r.status === 'rejected').length
          ],
          backgroundColor: ['#2e7d32', '#ffc107', '#f44336']
        }]
      }
    });
  }