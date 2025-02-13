<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'sku', 'barcode', 'description', 'price', 'cost',
        'quantity', 'category_id', 'supplier_id', 'reorder_point', 'status'
    ];

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // You may define additional relationships (category, supplier) if needed.
}
