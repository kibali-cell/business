<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'balance',
        'currency',
        'parent_account_id',
        'company_id'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'from_account_id')
            ->orWhere('to_account_id', $this->id);
    }

    public function parentAccount()
    {
        return $this->belongsTo(Account::class, 'parent_account_id');
    }
}