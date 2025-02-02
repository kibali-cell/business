<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'client_id',
        'due_date',
        'subtotal',
        'tax',
        'total',
        'status',
        'payment_terms',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2'
    ];

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }
}
