<?php

namespace App\Observers;

use App\Models\InventoryTransaction;
use App\Models\Product;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $transaction)
    {
        $product = $transaction->product;
        if ($transaction->type === 'in') {
            $product->increment('quantity', $transaction->quantity);
        } else {
            $product->decrement('quantity', $transaction->quantity);
        }
    }

    public function deleted(InventoryTransaction $transaction)
    {
        $product = $transaction->product;
        // When a transaction is deleted, reverse its effect.
        if ($transaction->type === 'in') {
            $product->decrement('quantity', $transaction->quantity);
        } else {
            $product->increment('quantity', $transaction->quantity);
        }
    }
}
