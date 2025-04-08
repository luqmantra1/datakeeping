<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Document extends Model
{
    protected $fillable = [
        'title',
        'file_path',
        'encrypted',
        'uploaded_by',
        'approved_by',
        'status'
    ];

    // Merge these two 'booted' methods into one
    protected static function booted()
{
    static::creating(function ($document) {
        // Encrypt file content if necessary
        if ($document->encrypted && request()->hasFile('file_path')) {
            $file = request()->file('file_path');
            $content = file_get_contents($file->getRealPath());

            // Check if content is empty
            if (empty($content)) {
                throw new \Exception("File content is empty.");
            }

            // Ensure the encryption key is valid
            $key = base64_decode(env('ENCRYPTION_KEY'));
            if (!$key) {
                throw new \Exception("Invalid ENCRYPTION_KEY in .env file.");
            }

            // IV must be 16 bytes for AES-256-CBC
            $iv = substr(hash('sha256', 'iv-secret'), 0, 16);

            // Encrypt the file content
            $encryptedContent = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);

            // Handle encryption failure
            if ($encryptedContent === false) {
                throw new \Exception("Encryption failed.");
            }

            // Save the encrypted content to the storage
            $path = 'documents/' . $file->getClientOriginalName() . '.enc';
            Storage::put($path, $encryptedContent);

            $document->file_path = $path;
        }

        // Set the uploaded_by field to the authenticated user
        $document->uploaded_by = Auth::id();
    });
}
    

    public function uploader()
{
    return $this->belongsTo(User::class, 'uploaded_by');
}


}
