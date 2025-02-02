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
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>Invoices</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                            Add Invoice
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Number</th>
                                    <th>Client</th>
                                    <th>Due Date</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->number }}</td>
                                        <td>{{ $invoice->client->name }}</td>
                                        <td>{{ $invoice->due_date }}</td>
                                        <td>{{ $invoice->total }}</td>
                                        <td>{{ $invoice->status }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editInvoiceModal{{ $invoice->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Invoice Modal -->
                                    <div class="modal fade" id="editInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="editInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editInvoiceModalLabel{{ $invoice->id }}">Edit Invoice</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finance.invoices.update', $invoice->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="number">Invoice Number</label>
                                                            <input type="text" name="number" class="form-control" value="{{ $invoice->number }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="client_id">Client</label>
                                                            <select name="client_id" class="form-control" required>
                                                                @foreach($clients as $client)
                                                                    <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="due_date">Due Date</label>
                                                            <input type="date" name="due_date" class="form-control" value="{{ $invoice->due_date }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="total">Total</label>
                                                            <input type="number" step="0.01" name="total" class="form-control" value="{{ $invoice->total }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="status">Status</label>
                                                            <select name="status" class="form-control" required>
                                                                <option value="unpaid" {{ $invoice->status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                                                <option value="paid" {{ $invoice->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                                <option value="overdue" {{ $invoice->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
                                                            </select>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Save changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Create Invoice Modal -->
        <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createInvoiceModalLabel">Create Invoice</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('finance.invoices.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="number">Invoice Number</label>
                                <input type="text" name="number" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="client_id">Client</label>
                                <select name="client_id" class="form-control" required>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" name="due_date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="total">Total</label>
                                <input type="number" step="0.01" name="total" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="unpaid">Unpaid</option>
                                    <option value="paid">Paid</option>
                                    <option value="overdue">Overdue</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        </div>

    @include('home.script')
  </body>
</html>