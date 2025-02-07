<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      
      <!-- Header -->
      @include('home.header')
      <!-- End Header -->
      
      <div class="container-fluid page-body-wrapper">
        
        <!-- Sidebar -->
        @include('home.sidebar')
        <!-- End Sidebar -->
        
        <!-- Main Content -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="row">
              <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                  <div class="card-body">
                    <h1 class="card-title">Submit New Expense</h1>
                    
                    <!-- Display Validation Errors -->
                    @if($errors->any())
                      <div class="alert alert-danger">
                        <ul class="mb-0">
                          @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    @endif

                    <!-- Expense Form -->
                    <form action="{{ route('finance.expenses.store') }}" method="POST">
                      @csrf
                      <div class="mb-3">
                        <label for="date" class="form-label">Expense Date</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                      </div>
                      <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ old('amount') }}" required>
                      </div>
                      <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" id="category" class="form-control" value="{{ old('category') }}">
                      </div>
                      <div class="mb-3">
                        <label for="description" class="form-label">Description / Details</label>
                        <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Submit Expense</button>
                      <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Main Content -->
      </div>

      <!-- Scripts -->
      @include('home.script')
    </div>
  </body>
</html>