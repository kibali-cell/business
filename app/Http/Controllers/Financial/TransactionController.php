<?php

namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with(['fromAccount', 'toAccount'])->paginate(15);
        return view('financial.transactions.index', compact('transactions'));
    }

    public function create()
    {
        $accounts = Account::all();
        return view('financial.transactions.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'reference_number' => 'required|string|unique:transactions',
            'description' => 'nullable|string'
        ]);

        Transaction::create($validated);
        return redirect()->route('financial.transactions.index')
            ->with('success', 'Transaction created successfully');
    }

    public function show(Transaction $transaction)
    {
        return view('financial.transactions.show', compact('transaction'));
    }

    public function edit(Transaction $transaction)
    {
        $accounts = Account::all();
        return view('financial.transactions.edit', compact('transaction', 'accounts'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|string',
            'from_account_id' => 'required|exists:accounts,id',
            'to_account_id' => 'required|exists:accounts,id',
            'reference_number' => 'required|string|unique:transactions,reference_number,' . $transaction->id,
            'description' => 'nullable|string'
        ]);

        $transaction->update($validated);
        return redirect()->route('financial.transactions.index')
            ->with('success', 'Transaction updated successfully');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('financial.transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }
}