<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'amount',
        'category',
        'description',
        'submitted_by',
        'approved_by',
        'status',
    ];

    // Relationship: The user who submitted this expense.
    public function submittedBy()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    // Relationship: The user (manager) who approved/rejected this expense.
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
