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
  <h1 class="mb-4">Warehouses</h1>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Button to trigger Create Warehouse Modal -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createWarehouseModal">
    <i class="mdi mdi-plus-circle me-2"></i> Add New Warehouse
  </button>

  <!-- Warehouses Table -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Location</th>
          <th>Capacity</th>
          <th>Manager ID</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($warehouses as $warehouse)
          <tr>
            <td>{{ $warehouse->name }}</td>
            <td>{{ $warehouse->location }}</td>
            <td>{{ $warehouse->capacity }}</td>
            <td>{{ $warehouse->manager_id }}</td>
            <td>
              <!-- View Button -->
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewWarehouseModal{{ $warehouse->id }}">
                <i class="mdi mdi-eye"></i>
              </button>
              <!-- Edit Button -->
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editWarehouseModal"
                      data-warehouse-id="{{ $warehouse->id }}"
                      data-name="{{ $warehouse->name }}"
                      data-location="{{ $warehouse->location }}"
                      data-capacity="{{ $warehouse->capacity }}"
                      data-manager-id="{{ $warehouse->manager_id }}">
                <i class="mdi mdi-pencil"></i>
              </button>
              <!-- Delete Form -->
              <form action="{{ route('warehouses.destroy', $warehouse->id) }}" method="POST" class="d-inline">
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

  {!! $warehouses->links() !!}
</div>

<!-- Create Warehouse Modal -->
<div class="modal fade" id="createWarehouseModal" tabindex="-1" aria-labelledby="createWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('warehouses.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createWarehouseModalLabel">Add New Warehouse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Warehouse Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" id="location" class="form-control">
          </div>
          <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="capacity" class="form-control">
          </div>
          <div class="mb-3">
            <label for="manager_id" class="form-label">Manager ID</label>
            <input type="number" name="manager_id" id="manager_id" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Warehouse</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Warehouse Modal -->
<div class="modal fade" id="editWarehouseModal" tabindex="-1" aria-labelledby="editWarehouseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editWarehouseForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editWarehouseModalLabel">Edit Warehouse</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_name" class="form-label">Warehouse Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_location" class="form-label">Location</label>
            <input type="text" name="location" id="edit_location" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" id="edit_capacity" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_manager_id" class="form-label">Manager ID</label>
            <input type="number" name="manager_id" id="edit_manager_id" class="form-control">
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

<!-- View Warehouse Modals -->
@foreach($warehouses as $warehouse)
<div class="modal fade" id="viewWarehouseModal{{ $warehouse->id }}" tabindex="-1" aria-labelledby="viewWarehouseModalLabel{{ $warehouse->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewWarehouseModalLabel{{ $warehouse->id }}">Warehouse Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr>
            <th>Name</th>
            <td>{{ $warehouse->name }}</td>
          </tr>
          <tr>
            <th>Location</th>
            <td>{{ $warehouse->location }}</td>
          </tr>
          <tr>
            <th>Capacity</th>
            <td>{{ $warehouse->capacity }}</td>
          </tr>
          <tr>
            <th>Manager ID</th>
            <td>{{ $warehouse->manager_id }}</td>
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

<!-- JavaScript for Edit Warehouse Modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editWarehouseModal = document.getElementById('editWarehouseModal');
    editWarehouseModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const warehouseId = button.getAttribute('data-warehouse-id');
        const name = button.getAttribute('data-name') || '';
        const location = button.getAttribute('data-location') || '';
        const capacity = button.getAttribute('data-capacity') || '';
        const managerId = button.getAttribute('data-manager-id') || '';

        const form = document.getElementById('editWarehouseForm');
        form.action = `/warehouses/${warehouseId}`;

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_location').value = location;
        document.getElementById('edit_capacity').value = capacity;
        document.getElementById('edit_manager_id').value = managerId;
    });
});
</script>
