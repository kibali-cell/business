<?php
namespace App\Http\Controllers\Financial;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('parentAccount')->paginate(15);
        return view('financial.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $parentAccounts = Account::all();
        return view('financial.accounts.create', compact('parentAccounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'currency' => 'required|string|size:3',
            'parent_account_id' => 'nullable|exists:accounts,id'
        ]);

        Account::create($validated);
        return redirect()->route('financial.accounts.index')
            ->with('success', 'Account created successfully');
    }

    // Add other methods (show, edit, update, destroy) as needed
}