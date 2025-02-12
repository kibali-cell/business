<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <style>
      /* Custom Enhancements */
      .budget-table {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      }
      .budget-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #495057;
      }
      .budget-table td {
        vertical-align: middle;
      }
      .action-buttons .btn {
        margin: 2px;
        font-size: 0.875rem;
      }
      .modal-content {
        border-radius: 10px;
      }
      .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
      }
      .modal-title {
        font-weight: 600;
      }
      .pagination {
        justify-content: center;
        margin-top: 20px;
      }
    </style>
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
              <h1 class="mb-4">Budget Planning & Monitoring</h1>

              @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session('success') }}
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
              @endif

              <!-- Create Budget Button -->
              <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createBudgetModal">
                <i class="mdi mdi-plus-circle me-2"></i>Create New Budget
              </button>

              <!-- Budget Table -->
              <div class="table-responsive">
                <table class="table budget-table">
                  <thead>
                    <tr>
                      <th>Department</th>
                      <th>Allocated</th>
                      <th>Actual</th>
                      <th>Variance</th>
                      <th>Period</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($budgets as $budget)
                    <tr>
                      <td>{{ $budget->department ?? 'General' }}</td>
                      <td>${{ number_format($budget->allocated, 2) }}</td>
                      <td>${{ number_format($budget->actual, 2) }}</td>
                      <td>${{ number_format($budget->variance, 2) }}</td>
                      <td>{{ \Carbon\Carbon::parse($budget->start_date)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($budget->end_date)->format('Y-m-d') }}</td>
                      <td class="action-buttons">
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewBudgetModal{{ $budget->id }}">
                          <i class="mdi mdi-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editBudgetModal"
                                data-budget-id="{{ $budget->id }}"
                                data-department="{{ $budget->department }}"
                                data-allocated="{{ $budget->allocated }}"
                                data-actual="{{ $budget->actual }}"
                                data-start-date="{{ $budget->start_date }}"
                                data-end-date="{{ $budget->end_date }}">
                          <i class="mdi mdi-pencil"></i>
                        </button>
                        <form action="{{ route('finance.budgets.destroy', $budget->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">
                            <i class="mdi mdi-delete"></i>
                          </button>
                        </form>
                      </td>
                    </tr>

                    
                    @endforeach
                  </tbody>
                </table>
              </div>

              <!-- Pagination -->
              {!! $budgets->links() !!}
            </div>
          </div>
        </div>
        <!-- End Main Panel Content -->

      </div>
    </div>

    <!-- Modals -->

<!-- View Budget Modal -->
<div class="modal fade" id="viewBudgetModal{{ $budget->id }}" tabindex="-1" aria-labelledby="viewBudgetModalLabel{{ $budget->id }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="viewBudgetModalLabel{{ $budget->id }}">Budget Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <table class="table table-bordered">
                              <tr>
                                <th>Department</th>
                                <td>{{ $budget->department ?? 'General' }}</td>
                              </tr>
                              <tr>
                                <th>Allocated</th>
                                <td>${{ number_format($budget->allocated, 2) }}</td>
                              </tr>
                              <tr>
                                <th>Actual</th>
                                <td>${{ number_format($budget->actual, 2) }}</td>
                              </tr>
                              <tr>
                                <th>Variance</th>
                                <td>${{ number_format($budget->variance, 2) }}</td>
                              </tr>
                              <tr>
                                <th>Period</th>
                                <td>{{ \Carbon\Carbon::parse($budget->start_date)->format('Y-m-d') }} to {{ \Carbon\Carbon::parse($budget->end_date)->format('Y-m-d') }}</td>
                              </tr>
                            </table>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>




    <!-- Create Budget Modal -->
    <div class="modal fade" id="createBudgetModal" tabindex="-1" aria-labelledby="createBudgetModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form action="{{ route('finance.budgets.store') }}" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title" id="createBudgetModalLabel">Create New Budget</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="department" class="form-label">Department (optional)</label>
                <input type="text" name="department" id="department" class="form-control" value="{{ old('department') }}">
              </div>
              <div class="mb-3">
                <label for="allocated" class="form-label">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="allocated" class="form-control" value="{{ old('allocated') }}" required>
              </div>
              <div class="mb-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', date('Y-m-d')) }}" required>
              </div>
              <div class="mb-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', date('Y-m-d', strtotime('+1 month'))) }}" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Create Budget</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Budget Modal -->
    <div class="modal fade" id="editBudgetModal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="editBudgetForm" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-header">
              <h5 class="modal-title" id="editBudgetModalLabel">Edit Budget</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="mb-3">
                <label for="edit_department" class="form-label">Department (optional)</label>
                <input type="text" name="department" id="edit_department" class="form-control">
              </div>
              <div class="mb-3">
                <label for="edit_allocated" class="form-label">Allocated Amount</label>
                <input type="number" step="0.01" name="allocated" id="edit_allocated" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="edit_actual" class="form-label">Actual Spending</label>
                <input type="number" step="0.01" name="actual" id="edit_actual" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="edit_start_date" class="form-label">Start Date</label>
                <input type="date" name="start_date" id="edit_start_date" class="form-control" required>
              </div>
              <div class="mb-3">
                <label for="edit_end_date" class="form-label">End Date</label>
                <input type="date" name="end_date" id="edit_end_date" class="form-control" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    @include('home.script')
    
    <!-- JavaScript for Edit Modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editBudgetModal = document.getElementById('editBudgetModal');
        editBudgetModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const budgetId = button.getAttribute('data-budget-id');
            const department = button.getAttribute('data-department') || '';
            const allocated = button.getAttribute('data-allocated') || '';
            const actual = button.getAttribute('data-actual') || '';
            const startDate = button.getAttribute('data-start-date') || '';
            const endDate = button.getAttribute('data-end-date') || '';

            const form = document.getElementById('editBudgetForm');
            form.action = `/finance/budgets/${budgetId}`;

            document.getElementById('edit_department').value = department;
            document.getElementById('edit_allocated').value = allocated;
            document.getElementById('edit_actual').value = actual;
            document.getElementById('edit_start_date').value = startDate;
            document.getElementById('edit_end_date').value = endDate;
        });
    });
    </script>
  </body>
</html>