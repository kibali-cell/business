<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $guarded = [];

    public function document() {
        return $this->belongsTo(Document::class);
    }

    public function uploader() {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeLatestVersion($query)
    {
        return $query->orderByDesc('created_at')->limit(1);
    }
}

