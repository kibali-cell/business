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
                        <h4>Accounts</h4>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createAccountModal">
                            Add Account
                        </button>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Balance</th>
                                    <th>Currency</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                    <tr>
                                        <td>{{ $account->name }}</td>
                                        <td>{{ $account->type }}</td>
                                        <td>{{ $account->balance }}</td>
                                        <td>{{ $account->currency }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editAccountModal{{ $account->id }}">
                                                Edit
                                            </button>
                                            <form action="{{ route('finance.accounts.destroy', $account->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Edit Account Modal -->
                                    <div class="modal fade" id="editAccountModal{{ $account->id }}" tabindex="-1" aria-labelledby="editAccountModalLabel{{ $account->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editAccountModalLabel{{ $account->id }}">Edit Account</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('finance.accounts.update', $account->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="name">Name</label>
                                                            <input type="text" name="name" class="form-control" value="{{ $account->name }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="type">Type</label>
                                                            <select name="type" class="form-control" required>
                                                                <option value="asset" {{ $account->type == 'asset' ? 'selected' : '' }}>Asset</option>
                                                                <option value="liability" {{ $account->type == 'liability' ? 'selected' : '' }}>Liability</option>
                                                                <option value="equity" {{ $account->type == 'equity' ? 'selected' : '' }}>Equity</option>
                                                                <option value="revenue" {{ $account->type == 'revenue' ? 'selected' : '' }}>Revenue</option>
                                                                <option value="expense" {{ $account->type == 'expense' ? 'selected' : '' }}>Expense</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="balance">Balance</label>
                                                            <input type="number" step="0.01" name="balance" class="form-control" value="{{ $account->balance }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="currency">Currency</label>
                                                            <input type="text" name="currency" class="form-control" value="{{ $account->currency }}" required>
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
      </div>
    </div>

    <!-- Create Account Modal -->
    <div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAccountModalLabel">Create Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('finance.accounts.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type</label>
                            <select name="type" class="form-control" required>
                                <option value="asset">Asset</option>
                                <option value="liability">Liability</option>
                                <option value="equity">Equity</option>
                                <option value="revenue">Revenue</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="balance">Balance</label>
                            <input type="number" step="0.01" name="balance" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="currency">Currency</label>
                            <input type="text" name="currency" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('home.script')
  </body>
</html>
