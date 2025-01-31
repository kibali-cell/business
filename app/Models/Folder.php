<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    protected $guarded = [];

    public function parent()
    {
        return $this->belongsTo(Folder::class, 'parent_id');
    }
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    public function children()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
