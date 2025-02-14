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
  <h1 class="mb-4">Inventory Management</h1>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Button to trigger Create Product Modal -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createProductModal">
    <i class="mdi mdi-plus-circle me-2"></i> Add New Product
  </button>

  <!-- Products Table -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>SKU</th>
          <th>Price</th>
          <th>Cost</th>
          <th>Quantity</th>
          <th>Reorder Point</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($products as $product)
          <tr @if($product->quantity < $product->reorder_point) class="table-warning" @endif>
            <td>{{ $product->name }}</td>
            <td>{{ $product->sku }}</td>
            <td>${{ number_format($product->price, 2) }}</td>
            <td>${{ number_format($product->cost, 2) }}</td>
            <td>{{ $product->quantity }}</td>
            <td>{{ $product->reorder_point }}</td>
            <td>{{ ucfirst($product->status) }}</td>
            <td class="action-buttons">
              <!-- View Button -->
              <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewProductModal{{ $product->id }}">
                <i class="mdi mdi-eye"></i>
              </button>
              <!-- Edit Button -->
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editProductModal"
                      data-product-id="{{ $product->id }}"
                      data-name="{{ $product->name }}"
                      data-sku="{{ $product->sku }}"
                      data-price="{{ $product->price }}"
                      data-cost="{{ $product->cost }}"
                      data-quantity="{{ $product->quantity }}"
                      data-reorder-point="{{ $product->reorder_point }}"
                      data-status="{{ $product->status }}"
                      data-barcode="{{ $product->barcode }}"
                      data-description="{{ $product->description }}">
                <i class="mdi mdi-pencil"></i>
              </button>
              <!-- Delete Form -->
              <form action="{{ route('inventory.destroy', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-sm btn-danger">
                  <i class="mdi mdi-delete"></i>
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {!! $products->links() !!}
</div>

<!-- Create Product Modal -->
<div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('inventory.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createProductModalLabel">Add New Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form fields for product creation -->
          <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="sku" class="form-label">SKU</label>
            <input type="text" name="sku" id="sku" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="barcode" class="form-label">Barcode (optional)</label>
            <input type="text" name="barcode" id="barcode" class="form-control">
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea name="description" id="description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="cost" class="form-label">Cost</label>
            <input type="number" step="0.01" name="cost" id="cost" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="quantity" class="form-label">Initial Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="reorder_point" class="form-label">Reorder Point</label>
            <input type="number" name="reorder_point" id="reorder_point" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add Product</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Product Modal (Single modal for editing) -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editProductForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Form fields for product editing -->
          <div class="mb-3">
            <label for="edit_name" class="form-label">Product Name</label>
            <input type="text" name="name" id="edit_name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_sku" class="form-label">SKU</label>
            <input type="text" name="sku" id="edit_sku" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_barcode" class="form-label">Barcode (optional)</label>
            <input type="text" name="barcode" id="edit_barcode" class="form-control">
          </div>
          <div class="mb-3">
            <label for="edit_description" class="form-label">Description</label>
            <textarea name="description" id="edit_description" class="form-control"></textarea>
          </div>
          <div class="mb-3">
            <label for="edit_price" class="form-label">Price</label>
            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_cost" class="form-label">Cost</label>
            <input type="number" step="0.01" name="cost" id="edit_cost" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="edit_quantity" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_reorder_point" class="form-label">Reorder Point</label>
            <input type="number" name="reorder_point" id="edit_reorder_point" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_status" class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-control" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
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

<!-- View Product Modal: Output separately after table -->
@foreach($products as $product)
  <div class="modal fade" id="viewProductModal{{ $product->id }}" tabindex="-1" aria-labelledby="viewProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewProductModalLabel{{ $product->id }}">Product Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <table class="table table-bordered">
            <tr>
              <th>Name</th>
              <td>{{ $product->name }}</td>
            </tr>
            <tr>
              <th>SKU</th>
              <td>{{ $product->sku }}</td>
            </tr>
            <tr>
              <th>Barcode</th>
              <td>{{ $product->barcode ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Description</th>
              <td>{{ $product->description ?? 'N/A' }}</td>
            </tr>
            <tr>
              <th>Price</th>
              <td>${{ number_format($product->price, 2) }}</td>
            </tr>
            <tr>
              <th>Cost</th>
              <td>${{ number_format($product->cost, 2) }}</td>
            </tr>
            <tr>
              <th>Quantity</th>
              <td>{{ $product->quantity }}</td>
            </tr>
            <tr>
              <th>Reorder Point</th>
              <td>{{ $product->reorder_point }}</td>
            </tr>
            <tr>
              <th>Status</th>
              <td>{{ ucfirst($product->status) }}</td>
            </tr>
          </table>

          <!-- Barcode Display -->
          <div class="barcode text-center mt-3">
            {!! DNS1D::getBarcodeHTML($product->barcode ?? $product->sku, 'C128') !!}
            <p>{{ $product->barcode ?? $product->sku }}</p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@endforeach

@include('home.script')

<!-- JavaScript for Edit Modal -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editProductModal = document.getElementById('editProductModal');
    editProductModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const productId = button.getAttribute('data-product-id');
        const name = button.getAttribute('data-name') || '';
        const sku = button.getAttribute('data-sku') || '';
        const price = button.getAttribute('data-price') || '';
        const cost = button.getAttribute('data-cost') || '';
        const quantity = button.getAttribute('data-quantity') || '';
        const reorderPoint = button.getAttribute('data-reorder-point') || '';
        const status = button.getAttribute('data-status') || '';
        const barcode = button.getAttribute('data-barcode') || '';
        const description = button.getAttribute('data-description') || '';

        const form = document.getElementById('editProductForm');
        form.action = `/inventory/${productId}`;

        document.getElementById('edit_name').value = name;
        document.getElementById('edit_sku').value = sku;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_cost').value = cost;
        document.getElementById('edit_quantity').value = quantity;
        document.getElementById('edit_reorder_point').value = reorderPoint;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_barcode').value = barcode;
        document.getElementById('edit_description').value = description;
    });
});
</script>
