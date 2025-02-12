<?php

namespace App\Services;

use App\Models\BankTransaction;
use Carbon\Carbon;

class BankIntegrationService
{
    /**
     * Simulate fetching transactions from an external bank API.
     *
     * In a real implementation, you would call the API, process the response,
     * and return an array of transactions.
     */
    public function fetchTransactions()
    {
        // Simulated transaction data (replace with real API calls)
        return [
            [
                'account_id' => 1,  // Assuming internal bank account ID 1
                'transaction_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'amount' => -150.75,
                'description' => 'Office Supplies Purchase',
                'external_reference' => 'EXT123456',
            ],
            [
                'account_id' => 1,
                'transaction_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'amount' => 1200.00,
                'description' => 'Client Payment',
                'external_reference' => 'EXT123457',
            ],
        ];
    }

    /**
     * Sync fetched transactions into our local database.
     */
    public function syncTransactions()
    {
        $transactions = $this->fetchTransactions();

        foreach ($transactions as $data) {
            // Check if the transaction already exists (by external reference)
            $existing = BankTransaction::where('external_reference', $data['external_reference'])->first();
            if (!$existing) {
                BankTransaction::create($data);
            }
        }

        return count($transactions);
    }
}
