<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <style>
      /* Custom styles for reports */
      .card {
        margin-bottom: 20px;
      }
      .alert ul {
        margin: 0;
        padding-left: 20px;
      }
    </style>
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- Header -->
      @include('home.header')
      <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        @include('home.sidebar')
        <!-- Main Content -->
        <div class="container my-4">
          <h1 class="mb-4">Inventory Reports</h1>

          <!-- Low Stock Alerts -->
          @if($lowStockProducts->isNotEmpty())
            <div class="alert alert-warning">
              <h5>Low Stock Alerts:</h5>
              <ul>
                @foreach($lowStockProducts as $product)
                  <li>{{ $product->name }} (Quantity: {{ $product->quantity }}, Reorder Point: {{ $product->reorder_point }})</li>
                @endforeach
              </ul>
            </div>
          @else
            <div class="alert alert-success">
              No low stock alerts.
            </div>
          @endif

          <!-- Inventory Valuation Card -->
          <div class="card">
            <div class="card-header">
              Inventory Valuation
            </div>
            <div class="card-body">
              <h5>Total Inventory Value:</h5>
              <p class="h4">${{ number_format($inventoryValue, 2) }}</p>
            </div>
          </div>

          <!-- Chart Section -->
          <div class="card">
            <div class="card-header">
              Inventory Value by Warehouse
            </div>
            <div class="card-body">
              <canvas id="inventoryChart" width="400" height="150"></canvas>
            </div>
          </div>
        </div>
        <!-- End Main Content -->
      </div>
    </div>

    @include('home.script')
    <!-- Load Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Make sure $valueByWarehouse is passed from the controller
        const warehouseData = @json($valueByWarehouse);
        const labels = warehouseData.map(item => "Warehouse " + item.warehouse_id);
        const dataValues = warehouseData.map(item => parseFloat(item.total_value));

        const ctx = document.getElementById('inventoryChart').getContext('2d');
        new Chart(ctx, {
          type: 'bar',
          data: {
            labels: labels,
            datasets: [{
              label: 'Inventory Value ($)',
              data: dataValues,
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
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
      });
    </script>
  </body>
</html>
