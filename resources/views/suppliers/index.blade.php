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
<div class="container my-4">
  <h1 class="mb-4">Suppliers</h1>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Button to trigger Create Supplier Modal -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createSupplierModal">
    <i class="mdi mdi-plus-circle me-2"></i> Add New Supplier
  </button>

  <!-- Suppliers Table -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($suppliers as $supplier)
          <tr>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->phone }}</td>
            <td>{{ $supplier->address }}</td>
            <td>
              <!-- View Button: triggers the corresponding modal -->
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewSupplierModal{{ $supplier->id }}">
                <i class="mdi mdi-eye"></i>
              </button>

              <!-- Edit Button: triggers the Edit Supplier Modal -->
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editSupplierModal"
                      data-supplier-id="{{ $supplier->id }}"
                      data-name="{{ $supplier->name }}"
                      data-email="{{ $supplier->email }}"
                      data-phone="{{ $supplier->phone }}"
                      data-address="{{ $supplier->address }}">
                <i class="mdi mdi-pencil"></i>
              </button>

              <!-- Delete Form -->
              <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
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

  {!! $suppliers->links() !!}
</div>

<!-- Create Supplier Modal -->
<div class="modal fade" id="createSupplierModal" tabindex="-1" aria-labelledby="createSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createSupplierModalLabel">Add New Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Supplier Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control">
          </div>
          <div class="mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="phone" class="form-control">
          </div>
          <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Supplier</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Supplier Modal -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editSupplierForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editSupplierModalLabel">Edit Supplier</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_name" class="form-label">Supplier Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" name="email" id="edit_email" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_phone" class="form-label">Phone</label>
            <input type="text" name="phone" id="edit_phone" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_address" class="form-label">Address</label>
            <textarea name="address" id="edit_address" class="form-control"></textarea>
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

<!-- View Supplier Modals: Place these after the main container so they don't display inline -->
@foreach($suppliers as $supplier)
<div class="modal fade" id="viewSupplierModal{{ $supplier->id }}" tabindex="-1" aria-labelledby="viewSupplierModalLabel{{ $supplier->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewSupplierModalLabel{{ $supplier->id }}">Supplier Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr>
            <th>Name</th>
            <td>{{ $supplier->name }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $supplier->email }}</td>
          </tr>
          <tr>
            <th>Phone</th>
            <td>{{ $supplier->phone }}</td>
          </tr>
          <tr>
            <th>Address</th>
            <td>{{ $supplier->address }}</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@include('home.script')

<script>
document.addEventListener('DOMContentLoaded', function () {
    const editSupplierModal = document.getElementById('editSupplierModal');
    editSupplierModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const supplierId = button.getAttribute('data-supplier-id');
        const name = button.getAttribute('data-name') || '';
        const email = button.getAttribute('data-email') || '';
        const phone = button.getAttribute('data-phone') || '';
        const address = button.getAttribute('data-address') || '';

        const form = document.getElementById('editSupplierForm');
        form.action = `/suppliers/${supplierId}`;

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_address').value = address;
    });
});
</script>
