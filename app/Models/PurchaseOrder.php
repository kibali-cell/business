<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'order_date', 'total_amount', 'status'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
