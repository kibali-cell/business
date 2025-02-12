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
       
<div class="container">
    <h1 class="mb-4">Bank Transactions</h1>

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
      </div>
    @endif

    <!-- Sync Button -->
    <form action="{{ route('finance.bank-transactions.sync') }}" method="POST" class="mb-3">
      @csrf
      <button type="submit" class="btn btn-primary">Sync Bank Transactions</button>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Date</th>
            <th>Amount</th>
            <th>Description</th>
            <th>External Reference</th>
          </tr>
        </thead>
        <tbody>
          @foreach($transactions as $transaction)
            <tr>
              <td>{{ $transaction->transaction_date }}</td>
              <td>${{ number_format($transaction->amount, 2) }}</td>
              <td>{{ $transaction->description }}</td>
              <td>{{ $transaction->external_reference }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $transactions->links() !!}
</div>

        </div>

    @include('home.script')
  </body>
</html>



