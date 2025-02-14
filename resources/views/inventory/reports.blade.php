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

  <!-- Inventory Valuation -->
  <div class="card mb-4">
    <div class="card-header">
      Inventory Valuation
    </div>
    <div class="card-body">
      <h5>Total Inventory Value:</h5>
      <p class="h4">${{ number_format($inventoryValue, 2) }}</p>
    </div>
  </div>
</div>
