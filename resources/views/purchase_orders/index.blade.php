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
  <h1 class="mb-4">Purchase Orders</h1>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- Button to trigger Create Purchase Order Modal -->
  <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPO_Modal">
    <i class="mdi mdi-plus-circle me-2"></i>New Purchase Order
  </button>

  <!-- Purchase Orders Table -->
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Order Date</th>
          <th>Supplier</th>
          <th>Total Amount</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($orders as $order)
        <tr>
          <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</td>
          <td>{{ $order->supplier->name }}</td>
          <td>${{ number_format($order->total_amount, 2) }}</td>
          <td>{{ ucfirst($order->status) }}</td>
          <td>
            <!-- View Button triggers the modal below -->
            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewPOModal{{ $order->id }}">
              <i class="mdi mdi-eye"></i>
            </button>
            <!-- Edit Button triggers the Edit Modal -->
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editPOModal"
                    data-order-id="{{ $order->id }}"
                    data-supplier-id="{{ $order->supplier_id }}"
                    data-order-date="{{ $order->order_date }}"
                    data-total-amount="{{ $order->total_amount }}"
                    data-status="{{ $order->status }}">
              <i class="mdi mdi-pencil"></i>
            </button>
            <!-- Delete Form -->
            <form action="{{ route('purchase_orders.destroy', $order->id) }}" method="POST" class="d-inline">
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

  {!! $orders->links() !!}
</div>

<!-- Create Purchase Order Modal -->
<div class="modal fade" id="createPO_Modal" tabindex="-1" aria-labelledby="createPO_ModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('purchase_orders.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="createPO_ModalLabel">New Purchase Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-control" required>
              <option value="">Select Supplier</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" id="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
          </div>
          <div class="mb-3">
            <label for="total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-control" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="received">Received</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Create Order</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Purchase Order Modal -->
<div class="modal fade" id="editPOModal" tabindex="-1" aria-labelledby="editPOModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editPOForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="editPOModalLabel">Edit Purchase Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="edit_supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="edit_supplier_id" class="form-control" required>
              <option value="">Select Supplier</option>
              @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label for="edit_order_date" class="form-label">Order Date</label>
            <input type="date" name="order_date" id="edit_order_date" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_total_amount" class="form-label">Total Amount</label>
            <input type="number" step="0.01" name="total_amount" id="edit_total_amount" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="edit_status" class="form-label">Status</label>
            <select name="status" id="edit_status" class="form-control" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="received">Received</option>
              <option value="cancelled">Cancelled</option>
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

<!-- View Purchase Order Modals -->
@foreach($orders as $order)
<div class="modal fade" id="viewPOModal{{ $order->id }}" tabindex="-1" aria-labelledby="viewPOModalLabel{{ $order->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewPOModalLabel{{ $order->id }}">Purchase Order Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-bordered">
          <tr>
            <th>Order Date</th>
            <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</td>
          </tr>
          <tr>
            <th>Supplier</th>
            <td>{{ $order->supplier->name }}</td>
          </tr>
          <tr>
            <th>Total Amount</th>
            <td>${{ number_format($order->total_amount, 2) }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>{{ ucfirst($order->status) }}</td>
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
  // JavaScript for Purchase Order Edit Modal
  const editPOModal = document.getElementById('editPOModal');
  editPOModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const orderId = button.getAttribute('data-order-id');
      const supplierId = button.getAttribute('data-supplier-id') || '';
      const orderDate = button.getAttribute('data-order-date') || '';
      const totalAmount = button.getAttribute('data-total-amount') || '';
      const status = button.getAttribute('data-status') || '';
      
      const form = document.getElementById('editPOForm');
      form.action = `/purchase-orders/${orderId}`;
      
      document.getElementById('edit_supplier_id').value = supplierId;
      document.getElementById('edit_order_date').value = orderDate;
      document.getElementById('edit_total_amount').value = totalAmount;
      document.getElementById('edit_status').value = status;
  });
});
</script>
