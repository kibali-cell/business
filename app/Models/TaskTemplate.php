<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTemplate extends Model
{
    //
    protected $fillable = ['name', 'description', 'checklist'];

    protected $casts = [
        'checklist' => 'array'
    ];
}
