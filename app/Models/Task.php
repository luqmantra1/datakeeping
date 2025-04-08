<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['project_id', 'assigned_to', 'title', 'description', 'status'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Task.php
public function user()
{
    return $this->belongsTo(User::class);  // Assuming each task is assigned to one user
}

}

