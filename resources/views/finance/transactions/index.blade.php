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
                        <h4>Transactions</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
                            Add Transaction
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
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
                                        <td>{{ $transaction->date }}</td>
                                        <td>{{ $transaction->type }}</td>
                                        <td>{{ $transaction->amount }}</td>
                                        <td>{{ $transaction->fromAccount->name }}</td>
                                        <td>{{ $transaction->toAccount->name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTransactionModal{{ $transaction->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('finance.transactions.destroy', $transaction->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Transaction Modal -->
                                    <div class="modal fade" id="editTransactionModal{{ $transaction->id }}" tabindex="-1" aria-labelledby="editTransactionModalLabel{{ $transaction->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editTransactionModalLabel{{ $transaction->id }}">Edit Transaction</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finance.transactions.update', $transaction->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="date">Date</label>
                                                            <input type="date" name="date" class="form-control" value="{{ $transaction->date }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="type">Type</label>
                                                            <select name="type" class="form-control" required>
                                                                <option value="income" {{ $transaction->type == 'income' ? 'selected' : '' }}>Income</option>
                                                                <option value="expense" {{ $transaction->type == 'expense' ? 'selected' : '' }}>Expense</option>
                                                                <option value="transfer" {{ $transaction->type == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="amount">Amount</label>
                                                            <input type="number" step="0.01" name="amount" class="form-control" value="{{ $transaction->amount }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="from_account_id">From Account</label>
                                                            <select name="from_account_id" class="form-control" required>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ $transaction->from_account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="to_account_id">To Account</label>
                                                            <select name="to_account_id" class="form-control" required>
                                                                @foreach($accounts as $account)
                                                                    <option value="{{ $account->id }}" {{ $transaction->to_account_id == $account->id ? 'selected' : '' }}>{{ $account->name }}</option>
                                                                @endforeach
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

        <!-- Create Transaction Modal -->
        <div class="modal fade" id="createTransactionModal" tabindex="-1" aria-labelledby="createTransactionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTransactionModalLabel">Create Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('finance.transactions.store') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="date">Date</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="type">Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="income">Income</option>
                                    <option value="expense">Expense</option>
                                    <option value="transfer">Transfer</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="amount">Amount</label>
                                <input type="number" step="0.01" name="amount" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="from_account_id">From Account</label>
                                <select name="from_account_id" class="form-control" required>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="to_account_id">To Account</label>
                                <select name="to_account_id" class="form-control" required>
                                    @foreach($accounts as $account)
                                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                                    @endforeach
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