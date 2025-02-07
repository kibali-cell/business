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
                    <h1 class="card-title">Expense Reports</h1>
                    
                    <!-- Success Message -->
                    @if(session('success'))
                      <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- Submit New Expense Button -->
                    <a href="{{ route('finance.expenses.create') }}" class="btn btn-primary mb-3">Submit New Expense</a>

                    <!-- Expense Table -->
                    <div class="table-responsive">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Submitted By</th>
                            <th>Approved By</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($expenses as $expense)
                            <tr>
                              <td>{{ $expense->date }}</td>
                              <td>{{ $expense->amount }}</td>
                              <td>{{ $expense->category }}</td>
                              <td>{{ ucfirst($expense->status) }}</td>
                              <td>{{ $expense->submittedBy->name ?? 'N/A' }}</td>
                              <td>{{ $expense->approvedBy->name ?? 'Pending' }}</td>
                              <td>
                                <!-- View Button -->
                                <a href="{{ route('finance.expenses.show', $expense->id) }}" class="btn btn-sm btn-info">View</a>

                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editExpenseModal"
                                        data-expense-id="{{ $expense->id }}"
                                        data-expense-date="{{ $expense->date }}"
                                        data-expense-amount="{{ $expense->amount }}"
                                        data-expense-category="{{ $expense->category }}"
                                        data-expense-description="{{ $expense->description }}">
                                  Edit
                                </button>

                                <!-- Delete Button -->
                                <form action="{{ route('finance.expenses.destroy', $expense->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</button>
                                </form>

                                <!-- Approve/Reject Button (For Managers/Admins) -->
                                @if($expense->status == 'pending' && in_array(auth()->user()->role, ['manager','admin']))
                                  <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#statusExpenseModal"
                                          data-expense-id="{{ $expense->id }}">
                                    Approve/Reject
                                  </button>
                                @endif
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>

                    <!-- Pagination -->
                    {!! $expenses->links() !!}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- End Main Content -->
      </div>

      <!-- Edit Expense Modal -->
      <div class="modal fade" id="editExpenseModal" tabindex="-1" aria-labelledby="editExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editExpenseModalLabel">Edit Expense</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="editExpenseForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="edit_date" class="form-label">Expense Date</label>
                  <input type="date" name="date" id="edit_date" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="edit_amount" class="form-label">Amount</label>
                  <input type="number" step="0.01" name="amount" id="edit_amount" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label for="edit_category" class="form-label">Category</label>
                  <input type="text" name="category" id="edit_category" class="form-control">
                </div>
                <div class="mb-3">
                  <label for="edit_description" class="form-label">Description / Details</label>
                  <textarea name="description" id="edit_description" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Approve/Reject Modal -->
      <div class="modal fade" id="statusExpenseModal" tabindex="-1" aria-labelledby="statusExpenseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="statusExpenseModalLabel">Update Expense Status</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="statusExpenseForm" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <select name="status" id="status" class="form-control" required>
                    <option value="pending">Pending</option>
                    <option value="approved">Approve</option>
                    <option value="rejected">Reject</option>
                  </select>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Scripts -->
      @include('home.script')
    </div>

    <!-- JavaScript to Handle Modals -->
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        // Edit Expense Modal
        const editExpenseModal = document.getElementById('editExpenseModal');
        editExpenseModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget; // Button that triggered the modal
          const expenseId = button.getAttribute('data-expense-id');
          const expenseDate = button.getAttribute('data-expense-date');
          const expenseAmount = button.getAttribute('data-expense-amount');
          const expenseCategory = button.getAttribute('data-expense-category');
          const expenseDescription = button.getAttribute('data-expense-description');

          // Update the form action URL
          const form = editExpenseModal.querySelector('#editExpenseForm');
          form.action = `/finance/expenses/${expenseId}`;

          // Populate the form fields
          editExpenseModal.querySelector('#edit_date').value = expenseDate;
          editExpenseModal.querySelector('#edit_amount').value = expenseAmount;
          editExpenseModal.querySelector('#edit_category').value = expenseCategory;
          editExpenseModal.querySelector('#edit_description').value = expenseDescription;
        });

        // Approve/Reject Modal
        const statusExpenseModal = document.getElementById('statusExpenseModal');
        statusExpenseModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget; // Button that triggered the modal
          const expenseId = button.getAttribute('data-expense-id');

          // Update the form action URL
          const form = statusExpenseModal.querySelector('#statusExpenseForm');
          form.action = `/finance/expenses/${expenseId}/status`;
        });
      });
    </script>
  </body>
</html>