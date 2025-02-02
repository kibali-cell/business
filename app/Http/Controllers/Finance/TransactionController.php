<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Index: List all transactions
    public function index()
    {
        $transactions = Transaction::with(['fromAccount', 'toAccount'])->paginate(10);
        $accounts = Account::all();
        return view('finance.transactions.index', compact('transactions', 'accounts'));
    }

    // Create: Show the form to create a new transaction
    public function create()
    {
        $accounts = Account::all();
        return view('finance.transactions.create', compact('accounts'));
    }

    // Store: Save a new transaction
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'reference_number' => 'required|string|unique:transactions',
            'description' => 'nullable|string',
        ]);

        Transaction::create($request->all());
        return redirect()->route('financial.transactions.index')->with('success', 'Transaction created successfully.');
    }

    // Show: Display a specific transaction
    public function show(Transaction $transaction)
    {
        return view('finance.transactions.show', compact('transaction'));
    }

    // Edit: Show the form to edit a transaction
    public function edit(Transaction $transaction)
    {
        $accounts = Account::all();
        return view('finance.transactions.edit', compact('transaction', 'accounts'));
    }

    // Update: Update a transaction
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'status' => 'required|string|max:255',
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'reference_number' => 'required|string|unique:transactions,reference_number,' . $transaction->id,
            'description' => 'nullable|string',
        ]);

        $transaction->update($request->all());
        return redirect()->route('financial.transactions.index')->with('success', 'Transaction updated successfully.');
    }

    // Destroy: Delete a transaction
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('financial.transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}