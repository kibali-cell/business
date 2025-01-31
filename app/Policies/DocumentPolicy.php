<?php
namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    public function view(User $user, Document $document) {
        return $document->users->contains($user) || $user->isAdmin();
    }

    public function edit(User $user, Document $document) {
        return $document->users()->where('user_id', $user->id)
            ->whereIn('permission', ['edit', 'manage'])->exists()
            || $user->isAdmin();
    }

    public function manage(User $user, Document $document) {
        return $document->users()->where('user_id', $user->id)
            ->where('permission', 'manage')->exists()
            || $user->isAdmin();
    }
}