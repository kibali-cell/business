<!DOCTYPE html>
<html lang="en">
  <head>
    @include('home.css')
    <style>
      /* Limit the maximum width of the card and center it */
      .card-dynamic {
          max-width: 1200px;
          margin: auto;
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
                      <h4>Transactions</h4>
                      <!-- Trigger Create Transaction Modal -->
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                        Add Transaction
                      </button>
                    </div>
                    <div class="card-body">
                      @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                      @endif
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>From Account</th>
                            <th>To Account</th>
                            <th>Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($transactions as $transaction)
                            <tr>
                              <td>{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</td>
                              <td>{{ ucfirst($transaction->type) }}</td>
                              <td>{{ $transaction->amount }}</td>
                              <td>{{ $transaction->fromAccount->name }}</td>
                              <td>{{ $transaction->toAccount->name }}</td>
                              <td>
                                <!-- View Button -->
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#viewTransactionModal{{ $transaction->id }}">
                                  View
                                </button>
                                <!-- Edit Button -->
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $transaction->id }}">
                                  Edit
                                </button>
                                <!-- Delete Form -->
                                <form action="{{ route('finance.transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    Delete
                                  </button>
                                </form>
                              </td>
                            </tr>

                            <!-- View Transaction Modal -->
                            <div class="modal fade" id="viewTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="viewTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="viewTransactionModalLabel{{ $transaction->id }}">Transaction Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                  </div>
                                  <div class="modal-body">
                                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}</p>
                                    <p><strong>Type:</strong> {{ ucfirst($transaction->type) }}</p>
                                    <p><strong>Amount:</strong> {{ $transaction->amount }}</p>
                                    <p><strong>From Account:</strong> {{ $transaction->fromAccount->name }}</p>
                                    <p><strong>To Account:</strong> {{ $transaction->toAccount->name }}</p>
                                    <p><strong>Status:</strong> {{ $transaction->status }}</p>
                                    <p><strong>Reference Number:</strong> {{ $transaction->reference_number }}</p>
                                    <p><strong>Description:</strong> {{ $transaction->description }}</p>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            <!-- Edit Transaction Modal -->
                            <div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <form action="{{ route('finance.transactions.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                      <h5 class="modal-title" id="editTransactionModalLabel{{ $transaction->id }}">Edit Transaction</h5>
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      <!-- Date -->
                                      <div class="mb-3">
                                        <label for="date" class="form-label">Date</label>
                                        <input type="date" name="date" class="form-control" value="{{ \Carbon\Carbon::parse($transaction->date)->format('Y-m-d') }}" required>
                                      </div>
                                      <!-- Type -->
                                      <div class="mb-3">
                                        <label for="type" class="form-label">Type</label>
                                        <select name="type" class="form-control" required>
                                          <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Income</option>
                                          <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Expense</option>
                                          <option value="transfer" {{ $transaction->type == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        </select>
                                      </div>
                                      <!-- Amount -->
                                      <div class="mb-3">
                                        <label for="amount" class="form-label">Amount</label>
                                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $transaction->amount }}" required>
                                      </div>
                                      <!-- From Account -->
                                      <div class="mb-3">
                                        <label for="from_account_id" class="form-label">From Account</label>
                                        <select name="from_account_id" class="form-control" required>
                                          @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ $transaction->from_account_id == $account->id ? 'selected' : '' }}>
                                              {{ $account->name }}
                                            </option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <!-- To Account -->
                                      <div class="mb-3">
                                        <label for="to_account_id" class="form-label">To Account</label>
                                        <select name="to_account_id" class="form-control" required>
                                          @foreach($accounts as $account)
                                            <option value="{{ $account->id }}" {{ $transaction->to_account_id == $account->id ? 'selected' : '' }}>
                                              {{ $account->name }}
                                            </option>
                                          @endforeach
                                        </select>
                                      </div>
                                      <!-- Status -->
                                      <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <input type="text" name="status" class="form-control" value="{{ $transaction->status }}" required>
                                      </div>
                                      <!-- Reference Number -->
                                      <div class="mb-3">
                                        <label for="reference_number" class="form-label">Reference Number</label>
                                        <input type="text" name="reference_number" class="form-control" value="{{ $transaction->reference_number }}" required>
                                      </div>
                                      <!-- Description -->
                                      <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea name="description" class="form-control">{{ $transaction->description }}</textarea>
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
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- End content-wrapper -->
        </div> <!-- End main-panel -->
      </div> <!-- End container-fluid page-body-wrapper -->
      
      <!-- Create Transaction Modal -->
      <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="{{ route('finance.transactions.store') }}" method="POST">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="createTransactionModalLabel">Create Transaction</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <!-- Date -->
                <div class="mb-3">
                  <label for="date" class="form-label">Date</label>
                  <input type="date" name="date" class="form-control" value="{{ old('date', date('Y-m-d')) }}" required>
                </div>
                <!-- Type -->
                <div class="mb-3">
                  <label for="type" class="form-label">Type</label>
                  <select name="type" class="form-control" required>
                    <option value="">Select Type</option>
                    <option value="income" {{ old('type') == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ old('type') == 'expense' ? 'selected' : '' }}>Expense</option>
                    <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                  </select>
                </div>
                <!-- Amount -->
                <div class="mb-3">
                  <label for="amount" class="form-label">Amount</label>
                  <input type="number" step="0.01" name="amount" class="form-control" value="{{ old('amount') }}" required>
                </div>
                <!-- Status -->
                <div class="mb-3">
                  <label for="status" class="form-label">Status</label>
                  <input type="text" name="status" class="form-control" value="{{ old('status', 'pending') }}" required>
                </div>
                <!-- From Account -->
                <div class="mb-3">
                  <label for="from_account_id" class="form-label">From Account</label>
                  <select name="from_account_id" class="form-control" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                      <option value="{{ $account->id }}" {{ old('from_account_id')==$account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <!-- To Account -->
                <div class="mb-3">
                  <label for="to_account_id" class="form-label">To Account</label>
                  <select name="to_account_id" class="form-control" required>
                    <option value="">Select Account</option>
                    @foreach($accounts as $account)
                      <option value="{{ $account->id }}" {{ old('to_account_id')==$account->id ? 'selected' : '' }}>
                        {{ $account->name }}
                      </option>
                    @endforeach
                  </select>
                </div>
                <!-- Reference Number -->
                <div class="mb-3">
                  <label for="reference_number" class="form-label">Reference Number</label>
                  <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" required>
                </div>
                <!-- Description -->
                <div class="mb-3">
                  <label for="description" class="form-label">Description</label>
                  <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      
      @include('home.script')
    </div> <!-- End container-scroller -->
  </body>
</html>
