<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'uploaded_by',
        'approved_by',
        'status'
    ];

    protected static function booted(): void
    {
        static::creating(function ($document) {
            // Automatically set the uploaded_by field if not set
            if (!$document->uploaded_by && Auth::check()) {
                $document->uploaded_by = Auth::id();
            }
        });
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Add the isProtected method to determine if the document needs additional protection
    public function isProtected()
    {
        // You can customize this logic based on your document's status or any other flag
        return $this->status === 'protected'; // Change 'protected' to your own status logic if needed
    }
}
