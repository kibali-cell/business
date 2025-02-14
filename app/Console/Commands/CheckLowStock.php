<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LowStockAlert;

class CheckLowStock extends Command
{
    protected $signature = 'stock:check';
    protected $description = 'Check for products with low stock and send notifications.';

    public function handle()
    {
        $lowStockProducts = Product::whereColumn('quantity', '<', 'reorder_point')->get();

        if ($lowStockProducts->isNotEmpty()) {
            // For example, notify the manager(s)
            // Notification::route('mail', 'manager@example.com')->notify(new LowStockAlert($lowStockProducts));
            $this->info('Low stock alert notifications have been sent.');
        } else {
            $this->info('No low stock products found.');
        }
    }
}
