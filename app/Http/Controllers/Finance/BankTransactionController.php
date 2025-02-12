<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Services\BankIntegrationService;
use Illuminate\Http\Request;


class BankTransactionController extends Controller
{
    protected $bankIntegrationService;

    public function __construct(BankIntegrationService $bankIntegrationService)
    {
        $this->bankIntegrationService = $bankIntegrationService;
    }

    /**
     * Trigger a manual sync of bank transactions.
     */
    public function sync()
    {
        $count = $this->bankIntegrationService->syncTransactions();

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
