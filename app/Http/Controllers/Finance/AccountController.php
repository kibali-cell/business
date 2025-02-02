<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Index: List all accounts
    public function index()
    {
        $accounts = Account::with('parentAccount')->paginate(10);
        return view('finance.accounts.index', compact('accounts'));
    }

    // Create: Show the form to create a new account
    public function create()
    {
        $parentAccounts = Account::whereNull('parent_account_id')->get();
        return view('finance.accounts.create', compact('parentAccounts'));
    }

    // Store: Save a new account
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'parent_account_id' => 'nullable|exists:accounts,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        Account::create($request->all());
        return redirect()->route('finance.accounts.index')->with('success', 'Account created successfully.');
    }

    // Show: Display a specific account
    public function show(Account $account)
    {
        return view('finance.accounts.show', compact('account'));
    }

    // Edit: Show the form to edit an account
    public function edit(Account $account)
    {
        $parentAccounts = Account::whereNull('parent_account_id')->get();
        return view('finance.accounts.edit', compact('account', 'parentAccounts'));
    }

    // Update: Update an account
    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'balance' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'parent_account_id' => 'nullable|exists:accounts,id',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $account->update($request->all());
        return redirect()->route('finance.accounts.index')->with('success', 'Account updated successfully.');
    }

    // Destroy: Delete an account
    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('finance.accounts.index')->with('success', 'Account deleted successfully.');
    }
}