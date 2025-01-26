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

        <!-- Main Content -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="container-fluid">
              <!-- Companies Table -->
              <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                  <h2 class="mb-0">Companies</h2>
                  <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#addCompanyModal">
                    <i class="mdi mdi-plus"></i> Add Company
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
                          <th>Address</th>
                          <th>Website</th>
                          <th>Actions</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($companies as $company)
                          <tr>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->address }}</td>
                            <td>
                              @if ($company->website)
                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                              @else
                                N/A
                              @endif
                            </td>
                            <td>
                            <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCompanyModal" 
                                    data-id="{{ $company->id }}" 
                                    data-name="{{ $company->name }}" 
                                    data-email="{{ $company->email }}" 
                                    data-phone="{{ $company->phone }}" 
                                    data-address="{{ $company->address }}"
                                    data-website="{{ $company->website }}"> <!-- Add this line -->
                              Edit
                            </button>

                            <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewCompanyModal" 
                                    data-name="{{ $company->name }}" 
                                    data-email="{{ $company->email }}" 
                                    data-phone="{{ $company->phone }}" 
                                    data-address="{{ $company->address }}"
                                    data-website="{{ $company->website }}"> <!-- Add this line -->
                              View
                            </button>
                            
                              <form action="{{ route('crm.companies.destroy', $company->id) }}" method="POST" class="d-inline">
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
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Company Modal -->
    <div class="modal fade" id="addCompanyModal" tabindex="-1" aria-labelledby="addCompanyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="addCompanyModalLabel">Add New Company</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Add Company Form -->
            <form action="{{ route('crm.companies.store') }}" method="POST">
              @csrf
              <div class="mb-3">
                <label for="company-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="company-name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="company-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="company-email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="company-phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="company-phone" name="phone">
              </div>
              <div class="mb-3">
                <label for="company-address" class="form-label">Address</label>
                <input type="text" class="form-control" id="company-address" name="address">
              </div>
              <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" class="form-control" id="website" name="website">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Company</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- View Company Modal -->
    <div class="modal fade" id="viewCompanyModal" tabindex="-1" aria-labelledby="viewCompanyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="viewCompanyModalLabel">Company Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label"><strong>Name:</strong></label>
              <p id="view-company-name" class="mb-0"></p>
            </div>
            <div class="mb-3">
              <label class="form-label"><strong>Email:</strong></label>
              <p id="view-company-email" class="mb-0"></p>
            </div>
            <div class="mb-3">
              <label class="form-label"><strong>Phone:</strong></label>
              <p id="view-company-phone" class="mb-0"></p>
            </div>
            <div class="mb-3">
              <label class="form-label"><strong>Address:</strong></label>
              <p id="view-company-address" class="mb-0"></p>
            </div>
            <div class="mb-3">
              <label class="form-label"><strong>Website:</strong></label>
              <p id="view-company-website" class="mb-0">
                @if ($company->website)
                  <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                @else
                  N/A
                @endif
              </p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Company Modal -->
    <div class="modal fade" id="editCompanyModal" tabindex="-1" aria-labelledby="editCompanyModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="editCompanyModalLabel">Edit Company</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <!-- Edit Company Form -->
            <form id="editCompanyForm" method="POST">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="edit-company-name" class="form-label">Name</label>
                <input type="text" class="form-control" id="edit-company-name" name="name" required>
              </div>
              <div class="mb-3">
                <label for="edit-company-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="edit-company-email" name="email" required>
              </div>
              <div class="mb-3">
                <label for="edit-company-phone" class="form-label">Phone</label>
                <input type="text" class="form-control" id="edit-company-phone" name="phone">
              </div>
              <div class="mb-3">
                <label for="edit-company-address" class="form-label">Address</label>
                <input type="text" class="form-control" id="edit-company-address" name="address">
              </div>
              <div class="mb-3">
                <label for="website" class="form-label">Website</label>
                <input type="url" class="form-control" id="website" name="website" value="{{ $company->website }}">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Company</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    @include('home.script')
    <script>
      // View Company Modal
      document.getElementById('viewCompanyModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const phone = button.getAttribute('data-phone');
        const address = button.getAttribute('data-address');

        // Update modal content
        document.getElementById('view-company-name').textContent = name;
        document.getElementById('view-company-email').textContent = email;
        document.getElementById('view-company-phone').textContent = phone;
        document.getElementById('view-company-address').textContent = address;
      });

      // Edit Company Modal
      document.getElementById('editCompanyModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget; // Button that triggered the modal
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');
        const phone = button.getAttribute('data-phone');
        const address = button.getAttribute('data-address');
        const website = button.getAttribute('data-website');        

        // Update modal content
        document.getElementById('edit-company-name').value = name;
        document.getElementById('edit-company-email').value = email;
        document.getElementById('edit-company-phone').value = phone;
        document.getElementById('edit-company-address').value = address;
        document.getElementById('edit-company-website').value = website;

        // Update form action
        const form = document.getElementById('editCompanyForm');
        form.action = `/crm/companies/${id}`;
      });
    </script>
  </body>
</html>