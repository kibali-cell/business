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


    // Add this to help with debugging
    protected static function boot()
    {
        parent::boot();
        
        static::retrieved(function ($model) {
            \Log::debug('Retrieved template:', $model->toArray());
        });
    }
}
