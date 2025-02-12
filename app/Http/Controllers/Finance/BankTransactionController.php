<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Services\BankIntegrationService;
use Illuminate\Http\Request;

class BankTransactionController extends Controller
{
    /**
     * Trigger a manual sync of bank transactions.
     */
    public function sync()
    {
        $service = new BankIntegrationService();
        $count = $service->syncTransactions();

        return redirect()->back()->with('success', "$count bank transactions have been synced successfully.");
    }

    /**
     * (Optional) Show a list of bank transactions.
     */
    public function index()
    {
        $transactions = \App\Models\BankTransaction::orderBy('transaction_date', 'desc')->paginate(15);
        return view('finance.bank-transactions.index', compact('transactions'));
    }
}
