<?php

namespace App\Services;

use App\Models\BankTransaction;
use Carbon\Carbon;

class BankIntegrationService
{
    /**
     * Simulate fetching transactions from an external bank API.
     *
     * In a real implementation, you would make an HTTP request here.
     *
     * @return array
     */
    public function fetchTransactions()
    {
        // Simulated data – replace this with a real API call.
        return [
            [
                'account_id' => 1,  // Replace with your internal bank account ID
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
     *
     * @return int The number of new transactions added.
     */
    public function syncTransactions()
    {
        $transactions = $this->fetchTransactions();
        $syncedCount = 0;

        foreach ($transactions as $data) {
            // Check if the transaction already exists using the external reference.
            $existing = BankTransaction::where('external_reference', $data['external_reference'])->first();
            if (!$existing) {
                BankTransaction::create($data);
                $syncedCount++;
            }
        }

        return $syncedCount;
    }
}
