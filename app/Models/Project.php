<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['title', 'description', 'ceo_id'];

    // Define the relationship for the CEO
    public function ceo()
    {
        return $this->belongsTo(User::class, 'ceo_id');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}

