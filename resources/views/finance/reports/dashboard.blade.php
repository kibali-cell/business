<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      
      <!-- partial:partials/_navbar.html -->
      @include('home.header')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_sidebar.html -->
        @include('home.sidebar')
        <!-- partial -->
        <div class="main-panel">
    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Date Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" 
                                           value="{{ request('start_date', now()->subMonth()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" 
                                           value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button class="btn btn-primary px-4" id="applyFilters">Apply Filters</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            
            <!-- Statistical Data -->

            <div class="row">
    <div class="col-sm-12">
        <div class="statistics-details d-flex align-items-center justify-content-between">
            <div>
                <p class="statistics-title">Total Revenue</p>
                <h3 class="rate-percentage">${{ number_format($totalRevenue, 2) }}</h3>
                @if(isset($revenueGrowth))
                <p class="text-{{ $revenueGrowth >= 0 ? 'success' : 'danger' }} d-flex">
                    <i class="mdi mdi-menu-{{ $revenueGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $revenueGrowth >= 0 ? '+' : '' }}{{ number_format($revenueGrowth, 1) }}%</span>
                </p>
                @endif
            </div>
            <div>
                <p class="statistics-title">Outstanding Receivables</p>
                <h3 class="rate-percentage">${{ number_format($outstandingReceivables, 2) }}</h3>
                @if(isset($receivablesGrowth))
                <p class="text-{{ $receivablesGrowth >= 0 ? 'success' : 'danger' }} d-flex">
                    <i class="mdi mdi-menu-{{ $receivablesGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $receivablesGrowth >= 0 ? '+' : '' }}{{ number_format($receivablesGrowth, 1) }}%</span>
                </p>
                @endif
            </div>
            <div>
                <p class="statistics-title">Total Expenses</p>
                <h3 class="rate-percentage">${{ number_format($totalExpenses, 2) }}</h3>
                @if(isset($expensesGrowth))
                <p class="text-{{ $expensesGrowth <= 0 ? 'success' : 'danger' }} d-flex">
                    <i class="mdi mdi-menu-{{ $expensesGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $expensesGrowth >= 0 ? '+' : '' }}{{ number_format($expensesGrowth, 1) }}%</span>
                </p>
                @endif
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Net Profit</p>
                <h3 class="rate-percentage">${{ number_format($profit, 2) }}</h3>
                @if(isset($profitGrowth))
                <p class="text-{{ $profitGrowth >= 0 ? 'success' : 'danger' }} d-flex">
                    <i class="mdi mdi-menu-{{ $profitGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $profitGrowth >= 0 ? '+' : '' }}{{ number_format($profitGrowth, 1) }}%</span>
                </p>
                @endif
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Profit Margin</p>
                <h3 class="rate-percentage">
                    @if($totalRevenue > 0)
                        {{ number_format(($profit / $totalRevenue) * 100, 1) }}%
                    @else
                        0.0%
                    @endif
                </h3>
                @if(isset($profitGrowth))
                <p class="text-{{ $profitGrowth >= 0 ? 'success' : 'danger' }} d-flex">
                    <i class="mdi mdi-menu-{{ $profitGrowth >= 0 ? 'up' : 'down' }}"></i>
                    <span>{{ $profitGrowth >= 0 ? '+' : '' }}{{ number_format($profitGrowth, 1) }}%</span>
                </p>
                @endif
            </div>
            <div class="d-none d-md-block">
                <p class="statistics-title">Base Currency</p>
                <h3 class="rate-percentage">{{ $baseCurrency }}</h3>
                <p class="text-muted d-flex">
                    <i class="mdi mdi-currency-usd"></i>
                    <span>Exchange Rate Base</span>
                </p>
            </div>
        </div>
    </div>
</div>

            <!-- Revenue Chart -->
            <div class="row">
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Monthly Revenue</h4>
                            <div class="chart-container" style="position: relative; height:300px;">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

        </div>

    @include('home.script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script>
    // Use the Laravel provided data
    const monthlyData = @json($monthlyData);

    // Initialize chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: monthlyData.map(data => 'Month ' + data.month),
            datasets: [{
                label: 'Revenue ($)',
                data: monthlyData.map(data => data.revenue),
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderColor: 'rgb(13, 110, 253)',
                borderWidth: 2,
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => '$' + value.toLocaleString()
                    }
                }
            }
        }
    });

    // Filter Handling
    document.getElementById('applyFilters').addEventListener('click', function() {
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