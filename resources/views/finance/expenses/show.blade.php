<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <style>
      /* Custom CSS for Expense Detail View */
      .card-title {
        font-size: 1.75rem;
        margin-bottom: 1rem;
      }
      .table th {
        width: 30%;
      }
      .badge {
        font-size: 0.9rem;
        padding: 0.5em 0.75em;
      }
      .action-buttons .btn {
        margin-right: 5px;
      }
    </style>
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
                <div class="card shadow-sm">
                  <div class="card-body">
                    <h1 class="card-title mb-4">Expense Details</h1>
                    
                    <!-- Back Button -->
                    <a href="{{ route('finance.expenses.index') }}" class="btn btn-secondary mb-3">Back to Expenses</a>
                    
                    <!-- Expense Details Table -->
                    <div class="table-responsive">
                      <table class="table table-striped table-hover">
                        <tbody>
                          <tr>
                            <th>Date</th>
                            <td>{{ $expense->date }}</td>
                          </tr>
                          <tr>
                            <th>Amount</th>
                            <td>{{ $expense->amount }}</td>
                          </tr>
                          <tr>
                            <th>Category</th>
                            <td>{{ $expense->category ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                            <th>Description</th>
                            <td>{{ $expense->description ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                            <th>Status</th>
                            <td>
                              <span class="badge 
                                @if($expense->status == 'approved') bg-success 
                                @elseif($expense->status == 'rejected') bg-danger 
                                @else bg-warning 
                                @endif">
                                {{ ucfirst($expense->status) }}
                              </span>
                            </td>
                          </tr>
                          <tr>
                            <th>Submitted By</th>
                            <td>{{ $expense->submittedBy->name ?? 'N/A' }}</td>
                          </tr>
                          <tr>
                            <th>Approved By</th>
                            <td>{{ $expense->approvedBy->name ?? 'Pending' }}</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="action-buttons mt-4">
                      @if($expense->status == 'pending' && in_array(auth()->user()->role, ['manager', 'admin']))
                        <!-- Approve Button -->
                        <form action="{{ route('finance.expenses.approve', $expense->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-success">Approve</button>
                        </form>
                        <!-- Reject Button -->
                        <form action="{{ route('finance.expenses.reject', $expense->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-danger">Reject</button>
                        </form>
                      @endif

                      <!-- Edit Button -->
                      <a href="{{ route('finance.expenses.edit', $expense->id) }}" class="btn btn-warning">Edit</a>

                      <!-- Delete Button -->
                      <form action="{{ route('finance.expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this expense?')">Delete</button>
                      </form>
                    </div>
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Main Content -->
        
      </div>
      <!-- End container-fluid page-body-wrapper -->
      
      <!-- Scripts -->
      @include('home.script')
    </div>
  </body>
</html>
