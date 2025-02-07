<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <style>
      /* Limit the maximum width of the invoice card and center it */
      .card-dynamic {
          max-width: 1200px;
          margin: auto;
      }
      /* Styles for the professional invoice view */
      .invoice-container {
          padding: 20px;
      }
      .invoice-header {
          border-bottom: 2px solid #ddd;
          margin-bottom: 20px;
          padding-bottom: 10px;
      }
      .invoice-footer {
          border-top: 2px solid #ddd;
          margin-top: 20px;
          padding-top: 10px;
          text-align: center;
      }
      .invoice-details p {
          margin: 5px 0;
      }
    </style>
  </head>
  <body class="with-welcome-text">
    <div class="container-scroller">
      <!-- Header -->
      @include('home.header')
      
      <!-- Page Body -->
      <div class="container-fluid page-body-wrapper">
        <!-- Sidebar -->
        @include('home.sidebar')
        
        <!-- Main Panel -->
        <div class="main-panel">
          <div class="content-wrapper">
            <div class="container my-4">
              <div class="row justify-content-center">
                <div class="col-12 card-dynamic">
                  <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                      <h4>Invoices</h4>
                      <!-- Trigger Create Invoice Modal -->
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createInvoiceModal">
                        Add Invoice
                      </button>
                    </div>
                    <div class="card-body">
                      @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                      @endif
                      <!-- Invoice Table: Key Columns Only -->
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Invoice #</th>
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
                              <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}</td>
                              <td>{{ $invoice->total }}</td>
                              <td>{{ $invoice->status }}</td>
                              <td>
                                <!-- View Button: Opens Professional Invoice Modal -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewInvoiceModal{{ $invoice->id }}">
                                  View
                                </button>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editInvoiceModal{{ $invoice->id }}">
                                  Edit
                                </button>
                                <!-- Delete Form -->
                                <form action="{{ route('finance.invoices.destroy', $invoice->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                    Delete
                                  </button>
                                </form>
                              </td>
                            </tr>
    
                            <!-- View Invoice Modal (Professional Layout) -->
                            <div class="modal fade" id="viewInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="viewInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                              <div class="modal-dialog modal-xl">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="viewInvoiceModalLabel{{ $invoice->id }}">Invoice Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <div class="invoice-container">
                                      <div class="invoice-header">
                                        <h2>Invoice #{{ $invoice->number }}</h2>
                                        <p><strong>Date Issued:</strong> {{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}</p>
                                        <p><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}</p>
                                      </div>
                                      <div class="invoice-details">
                                        <h4>Bill To:</h4>
                                        <p>{{ $invoice->client->name }}</p>
                                        <!-- You can add more client details here if available -->
                                        <hr>
                                        <h4>Invoice Summary:</h4>
                                        <p><strong>Subtotal:</strong> {{ $invoice->subtotal }}</p>
                                        <p><strong>Tax:</strong> {{ $invoice->tax }}</p>
                                        <p><strong>Total:</strong> {{ $invoice->total }}</p>
                                        <p><strong>Status:</strong> {{ $invoice->status }}</p>
                                        <p><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</p>
                                        <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
                                      </div>
                                      <div class="invoice-footer">
                                        <button class="btn btn-primary" onclick="window.print()">Print Invoice</button>
                                        <a href="{{ route('finance.invoices.download', $invoice->id) }}" class="btn btn-success">
                                          Download PDF
                                        </a>
                                        <!-- Optionally, add a "Send Email" button here -->
                                        <button class="btn btn-info">Email Invoice</button>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!-- End View Invoice Modal -->
    
                            <!-- Edit Invoice Modal -->
                            <div class="modal fade" id="editInvoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="editInvoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form action="{{ route('finance.invoices.update', $invoice->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="editInvoiceModalLabel{{ $invoice->id }}">Edit Invoice</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <div class="mb-3">
                                        <label for="number" class="form-label">Invoice #</label>
                                        <input type="text" name="number" class="form-control" value="{{ $invoice->number }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="client_id" class="form-label">Client</label>
                                        <select name="client_id" class="form-control" required>
                                          <option value="">Select Client</option>
                                          @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ $invoice->client_id == $client->id ? 'selected' : '' }}>
                                              {{ $client->name }}
                                            </option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <div class="mb-3">
                                        <label for="due_date" class="form-label">Due Date</label>
                                        <input type="date" name="due_date" class="form-control" value="{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="subtotal" class="form-label">Subtotal</label>
                                        <input type="number" step="0.01" name="subtotal" class="form-control" value="{{ $invoice->subtotal }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="tax" class="form-label">Tax</label>
                                        <input type="number" step="0.01" name="tax" class="form-control" value="{{ $invoice->tax }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="total" class="form-label">Total</label>
                                        <input type="number" step="0.01" name="total" class="form-control" value="{{ $invoice->total }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <input type="text" name="status" class="form-control" value="{{ $invoice->status }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="payment_terms" class="form-label">Payment Terms</label>
                                        <input type="text" name="payment_terms" class="form-control" value="{{ $invoice->payment_terms }}" required>
                                      </div>
                                      <div class="mb-3">
                                        <label for="notes" class="form-label">Notes</label>
                                        <textarea name="notes" class="form-control">{{ $invoice->notes }}</textarea>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!-- End Edit Invoice Modal -->
    
                          @endforeach
                        </tbody>
                      </table>
                      <div class="d-flex justify-content-center">
                        {!! $invoices->links() !!}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- End content-wrapper -->
        </div> <!-- End main-panel -->
      </div> <!-- End container-fluid page-body-wrapper -->
      
      <!-- Create Invoice Modal -->
      <div class="modal fade" id="createInvoiceModal" tabindex="-1" aria-labelledby="createInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <!-- Predicted Invoice Number Section -->
            <div class="mb-3 p-3">
              <label for="predictedNumber" class="form-label">Invoice Number</label>
              <input type="text" id="predictedNumber" class="form-control" value="{{ $predictedNumber }}" readonly>
              <small class="form-text text-muted">
                (This is a predicted number and may change after submission.)
              </small>
            </div>
            <form action="{{ route('finance.invoices.store') }}" method="POST">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="createInvoiceModalLabel">Create Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Since the invoice number is auto-generated, we don't include an editable number field -->
                <div class="mb-3">
                  <label for="client_id" class="form-label">Client</label>
                  <select name="client_id" class="form-control" required>
                    <option value="">Select Client</option>
                    @foreach($clients as $client)
                      <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <div class="mb-3">
                  <label for="due_date" class="form-label">Due Date</label>
                  <input type="date" name="due_date" class="form-control" value="{{ old('due_date', date('Y-m-d')) }}" required>
                </div>
                <div class="mb-3">
                  <label for="subtotal" class="form-label">Subtotal</label>
                  <input type="number" step="0.01" name="subtotal" class="form-control" value="{{ old('subtotal') }}" required>
                </div>
                <div class="mb-3">
                  <label for="tax" class="form-label">Tax</label>
                  <input type="number" step="0.01" name="tax" class="form-control" value="{{ old('tax') }}" required>
                </div>
                <div class="mb-3">
                  <label for="total" class="form-label">Total</label>
                  <input type="number" step="0.01" name="total" class="form-control" value="{{ old('total') }}" required>
                </div>
                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <input type="text" name="status" class="form-control" value="{{ old('status', 'pending') }}" required>
                </div>
                <div class="mb-3">
                  <label for="payment_terms" class="form-label">Payment Terms</label>
                  <input type="text" name="payment_terms" class="form-control" value="{{ old('payment_terms') }}" required>
                </div>
                <div class="mb-3">
                  <label for="notes" class="form-label">Notes</label>
                  <textarea name="notes" class="form-control">{{ old('notes') }}</textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Invoice</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      @include('home.script')
    </div> <!-- End container-scroller -->
  </body>
</html>
