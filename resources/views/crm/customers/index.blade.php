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

        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <div class="card">
              <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
  <h2 class="mb-0">Customers</h2>
  <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addCustomerModal">
    <i class="mdi mdi-plus"></i> Add Customer
  </button>
</div>
                <div class="card-body">
                  @if(session('success'))
                    <div class="alert alert-success">
                      {{ session('success') }}
                    </div>
                  @endif
                  <div class="table-responsive">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($customers as $customer)
                        <tr>
                          <td>{{ $customer->name }}</td>
                          <td>{{ $customer->email }}</td>
                          <td>{{ $customer->phone }}</td>
                          <td>{{ $customer->status }}</td>
                          <td>
                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCustomerModal" 
                                    data-name="{{ $customer->name }}" 
                                    data-email="{{ $customer->email }}" 
                                    data-phone="{{ $customer->phone }}" 
                                    data-status="{{ $customer->status }}">
                              View
                            </button>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCustomerModal" 
                                    data-id="{{ $customer->id }}" 
                                    data-name="{{ $customer->name }}" 
                                    data-email="{{ $customer->email }}" 
                                    data-phone="{{ $customer->phone }}" 
                                    data-status="{{ $customer->status }}">
                              Edit
                            </button>
                            <form action="{{ route('crm.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  </div>
                  {{ $customers->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Add Customer Modal -->
        <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addCustomerModalLabel">Add New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Add Customer Form -->
                <form action="{{ route('crm.customers.store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone">
                  </div>
                  <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-control" id="status" name="status" required>
                      <option value="lead">Lead</option>
                      <option value="customer">Customer</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Customer</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- View Customer Modal -->
        <div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-labelledby="viewCustomerModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="viewCustomerModalLabel">Customer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="mb-3">
                  <label class="form-label"><strong>Name:</strong></label>
                  <p id="view-name"></p>
                </div>
                <div class="mb-3">
                  <label class="form-label"><strong>Email:</strong></label>
                  <p id="view-email"></p>
                </div>
                <div class="mb-3">
                  <label class="form-label"><strong>Phone:</strong></label>
                  <p id="view-phone"></p>
                </div>
                <div class="mb-3">
                  <label class="form-label"><strong>Status:</strong></label>
                  <p id="view-status"></p>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit Customer Modal -->
        <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editCustomerModalLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Edit Customer Form -->
                <form id="editCustomerForm" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="mb-3">
                    <label for="edit-name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="edit-name" name="name" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit-email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="edit-email" name="email" required>
                  </div>
                  <div class="mb-3">
                    <label for="edit-phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="edit-phone" name="phone">
                  </div>
                  <div class="mb-3">
                    <label for="edit-status" class="form-label">Status</label>
                    <select class="form-control" id="edit-status" name="status" required>
                      <option value="lead">Lead</option>
                      <option value="customer">Customer</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update Customer</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    @include('home.script')
    <script>
      // View Customer Modal
      document.getElementById('viewCustomerModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const phone = button.getAttribute('data-phone');
        const status = button.getAttribute('data-status');

        // Update modal content
        document.getElementById('view-name').textContent = name;
        document.getElementById('view-email').textContent = email;
        document.getElementById('view-phone').textContent = phone;
        document.getElementById('view-status').textContent = status;
      });

      // Edit Customer Modal
      document.getElementById('editCustomerModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const phone = button.getAttribute('data-phone');
        const status = button.getAttribute('data-status');

        // Update modal content
        document.getElementById('edit-name').value = name;
        document.getElementById('edit-email').value = email;
        document.getElementById('edit-phone').value = phone;
        document.getElementById('edit-status').value = status;

        // Update form action
        const form = document.getElementById('editCustomerForm');
        form.action = `/crm/customers/${id}`;
      });
    </script>
  </body>
</html>