<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // Add role to fillable if you want mass assignment
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Add these role check methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    // Document access methods
    public function accessibleDocuments()
    {
        return Document::where(function ($query) {
            $query->whereHas('users', fn($q) => $q->where('user_id', $this->id))
                  ->orWhere('uploaded_by', $this->id);
        })
        ->when(!$this->isAdmin(), fn($q) => $q->where('status', 'published'))
        ->with(['currentVersion', 'folder', 'uploader']);
    }

    public function hasDocumentAccess(Document $document, string $requiredPermission = 'view'): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        // Direct uploader gets full access
        if ($document->uploaded_by === $this->id) {
            return true;
        }

        $permission = $document->users()
            ->where('user_id', $this->id)
            ->value('permission');

        return match($requiredPermission) {
            'view' => in_array($permission, ['view', 'edit', 'manage']),
            'edit' => in_array($permission, ['edit', 'manage']),
            'manage' => $permission === 'manage',
            default => false
        };
    }

    // Relationship for documents the user has uploaded
    public function uploadedDocuments()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }

    // Relationship for documents shared with the user
    public function sharedDocuments()
    {
        return $this->belongsToMany(Document::class)
            ->withPivot('permission')
            ->withTimestamps();
    }
}