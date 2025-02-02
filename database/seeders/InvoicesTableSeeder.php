<?php

// database/seeders/InvoicesTableSeeder.php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class InvoicesTableSeeder extends Seeder
{
    public function run()
    {
        // Ensure there is at least one customer
        $customer = Customer::firstOrCreate([
            'name' => 'Example Customer',
            'email' => 'customer@example.com',
        ], [
            'phone' => '987-654-3210',
            'status' => 'active', // Remove 'address' if it doesn't exist
        ]);

        $invoices = [
            [
                'number' => 'INV-001',
                'client_id' => $customer->id,
                'due_date' => now()->addDays(30),
                'subtotal' => 1000.00,
                'tax' => 100.00,
                'total' => 1100.00,
                'status' => 'unpaid',
                'payment_terms' => 'Net 30',
                'notes' => 'Invoice for services rendered',
            ],
            [
                'number' => 'INV-002',
                'client_id' => $customer->id,
                'due_date' => now()->addDays(15),
                'subtotal' => 500.00,
                'tax' => 50.00,
                'total' => 550.00,
                'status' => 'paid',
                'payment_terms' => 'Net 15',
                'notes' => 'Invoice for product delivery',
            ],
        ];

        foreach ($invoices as $invoice) {
            Invoice::create($invoice);
        }
    }
}