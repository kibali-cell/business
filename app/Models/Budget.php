<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'department',
        'allocated',
        'actual',
        'start_date',
        'end_date',
    ];

    /**
     * Accessor to calculate the variance (allocated minus actual).
     */
    public function getVarianceAttribute()
    {
        return $this->allocated - $this->actual;
    }
}
