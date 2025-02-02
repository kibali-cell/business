<?php

// database/seeders/AccountsTableSeeder.php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Company;
use Illuminate\Database\Seeder;

class AccountsTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there is at least one company
        $company = Company::firstOrCreate([
            'name' => 'Example Company',
            'email' => 'example@example.com',
        ], [
            'phone' => '123-456-7890',
            'address' => '123 Main St',
            'website' => 'https://example.com',
        ]);

        $accounts = [
            [
                'name' => 'Cash',
                'type' => 'asset',
                'balance' => 10000.00,
                'currency' => 'USD',
                'parent_account_id' => null,
                'company_id' => $company->id,
            ],
            [
                'name' => 'Bank Account',
                'type' => 'asset',
                'balance' => 50000.00,
                'currency' => 'USD',
                'parent_account_id' => null,
                'company_id' => $company->id,
            ],
            [
                'name' => 'Accounts Receivable',
                'type' => 'asset',
                'balance' => 20000.00,
                'currency' => 'USD',
                'parent_account_id' => null,
                'company_id' => $company->id,
            ],
            [
                'name' => 'Accounts Payable',
                'type' => 'liability',
                'balance' => 15000.00,
                'currency' => 'USD',
                'parent_account_id' => null,
                'company_id' => $company->id,
            ],
        ];

        foreach ($accounts as $account) {
            Account::create($account);
        }
    }
}