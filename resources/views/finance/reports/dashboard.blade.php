<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      @include('home.header')
      <div class="container-fluid page-body-wrapper">
        @include('home.sidebar')
        
        <!-- Main Panel Content -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="container">
              <h1 class="mb-4">Financial Dashboard</h1>

              <!-- Filter Controls -->
              <div class="row mb-4">
                  <div class="col-md-4">
                      <label for="start_date" class="form-label">Start Date</label>
                      <input type="date" id="start_date" class="form-control" 
                             value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}">
                  </div>
                  <div class="col-md-4">
                      <label for="end_date" class="form-label">End Date</label>
                      <input type="date" id="end_date" class="form-control" 
                             value="{{ request('end_date', now()->format('Y-m-d')) }}">
                  </div>
                  <div class="col-md-4 d-flex align-items-end">
                      <button class="btn btn-primary" id="applyFilters">Apply Filters</button>
                  </div>
              </div>

              <!-- Key Metrics Cards -->
              <div class="row mb-4">
                  <div class="col-md-3 mb-3">
                      <div class="card text-white bg-success">
                          <div class="card-body">
                              <h5 class="card-title">Total Revenue</h5>
                              <p class="card-text">${{ number_format($totalRevenue, 2) }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3 mb-3">
                      <div class="card text-white bg-warning">
                          <div class="card-body">
                              <h5 class="card-title">Outstanding Receivables</h5>
                              <p class="card-text">${{ number_format($outstandingReceivables, 2) }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3 mb-3">
                      <div class="card text-white bg-danger">
                          <div class="card-body">
                              <h5 class="card-title">Total Expenses</h5>
                              <p class="card-text">${{ number_format($totalExpenses, 2) }}</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3 mb-3">
                      <div class="card text-white bg-info">
                          <div class="card-body">
                              <h5 class="card-title">Profit</h5>
                              <p class="card-text">${{ number_format($profit, 2) }}</p>
                          </div>
                      </div>
                  </div>
              </div>

              <!-- Monthly Revenue Chart -->
              <div class="card mb-4">
                  <div class="card-header">
                      Monthly Revenue (Last 6 Months)
                  </div>
                  <div class="card-body">
                      <canvas id="revenueChart" width="400" height="150"></canvas>
                  </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Main Panel Content -->

      </div>
    </div>

    @include('home.script')
    
    <!-- Chart.js and Custom Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart Implementation
        const monthlyData = @json($monthlyRevenue);
        const labels = monthlyData.map(data => 'Month ' + data.month);
        const revenue = monthlyData.map(data => data.revenue);

        const ctx = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Monthly Revenue',
                    data: revenue,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        });

        // Filter Handling
        document.getElementById('applyFilters').addEventListener('click', function () {
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;
            const url = new URL(window.location);
            url.searchParams.set('start_date', startDate);
            url.searchParams.set('end_date', endDate);
            window.location = url.toString();
        });
    </script>
  </body>
</html>