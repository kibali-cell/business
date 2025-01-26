<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'description', 'status', 'assigned_to', 'due_date', 'priority', 'template_id'
    ];

    protected $casts = [
        'due_date' => 'date', // Cast due_date to a Carbon instance
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function template()
    {
        return $this->belongsTo(TaskTemplate::class);
    }
}