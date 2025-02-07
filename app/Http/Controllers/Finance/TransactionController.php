<?php
// app/Http/Controllers/Finance/TransactionController.php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // List all transactions
    public function index()
    {
        $transactions = Transaction::with('fromAccount', 'toAccount')->get();
        $accounts = Account::all(); // Load all accounts

        // Pass both variables to your view. Adjust the view name as needed.
        return view('finance.transactions.index', compact('transactions', 'accounts'));
    }

    // Show form to create a new transaction
    public function create()
    {
        $accounts = Account::all();
        return view('transactions.create', compact('accounts'));
    }

    // Store a new transaction in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'              => 'required|date',
            'type'              => 'required|string',
            'amount'            => 'required|numeric|min:0',
            'status'            => 'required|string',
            'from_account_id'   => 'required|exists:accounts,id',
            'to_account_id'     => 'required|exists:accounts,id',
            'reference_number'  => 'required|string|unique:transactions,reference_number',
            'description'       => 'nullable|string',
        ]);

        // If you are using an input of type "date", it provides YYYY-MM-DD.
        // Convert it to a DateTime string if necessary.
        // For example, you could do: $validated['date'] .= ' 00:00:00';

        Transaction::create($validated);
        return redirect()->route('finance.transactions.index')->with('success', 'Transaction created successfully.');
    }

    // Show form to edit an existing transaction
    public function edit(Transaction $transaction)
    {
        $accounts = Account::all();
        return view('transactions.edit', compact('transaction', 'accounts'));
    }

    // Update an existing transaction
    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'date'              => 'required|date',
            'type'              => 'required|string',
            'amount'            => 'required|numeric|min:0',
            'status'            => 'required|string',
            'from_account_id'   => 'required|exists:accounts,id',
            'to_account_id'     => 'required|exists:accounts,id',
            'reference_number'  => 'required|string|unique:transactions,reference_number,'.$transaction->id,
            'description'       => 'nullable|string',
        ]);

        $transaction->update($validated);
        return redirect()->route('finance.transactions.index')->with('success', 'Transaction updated successfully.');
    }

    // Delete a transaction
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('finance.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
