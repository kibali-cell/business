<?php

// database/seeders/TransactionsTableSeeder.php

namespace Database\Seeders;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    public function run()
    {
        // Get valid account IDs
        $cashAccount = Account::where('name', 'Cash')->first();
        $bankAccount = Account::where('name', 'Bank Account')->first();
        $accountsPayable = Account::where('name', 'Accounts Payable')->first();

        if (!$cashAccount || !$bankAccount || !$accountsPayable) {
            $this->command->error('Required accounts do not exist. Please run the AccountsTableSeeder first.');
            return;
        }

        $transactions = [
            [
                'date' => now(),
                'type' => 'transfer',
                'amount' => 1000.00,
                'status' => 'completed',
                'from_account_id' => $cashAccount->id,
                'to_account_id' => $bankAccount->id,
                'reference_number' => 'TXN123456',
                'description' => 'Funds transfer to bank account',
            ],
            [
                'date' => now(),
                'type' => 'payment',
                'amount' => 500.00,
                'status' => 'completed',
                'from_account_id' => $bankAccount->id,
                'to_account_id' => $accountsPayable->id,
                'reference_number' => 'TXN654321',
                'description' => 'Vendor payment',
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::create($transaction);
        }
    }
}