<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Document;
use Illuminate\Http\Response;

class SecureDownloadController extends Controller
{
    public function show($id)
    {
        // Retrieve the document by its ID
        $document = Document::findOrFail($id);

        // Check if the document is encrypted
        if ($document->encrypted) {
            // Ensure only Admin or CEO can download encrypted files
            if (!Auth::check() || (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('CEO'))) {
                abort(403, 'Unauthorized');
            }

            // Decrypt the document content
            $encryptedData = Storage::get($document->file_path);
            $key = base64_decode(env('ENCRYPTION_KEY')); // Retrieve encryption key from .env
            $iv = substr(hash('sha256', 'iv-secret'), 0, 16); // IV must be 16 bytes

            // Perform the decryption
            $decrypted = openssl_decrypt($encryptedData, 'AES-256-CBC', $key, 0, $iv);

            // If decryption fails, return an error
            if ($decrypted === false) {
                abort(500, 'Failed to decrypt the file.');
            }

            // Return the decrypted file as a download
            return response($decrypted)
                ->header('Content-Type', 'application/octet-stream')
                ->header('Content-Disposition', 'attachment; filename="' . basename($document->file_path, '.enc') . '"');
        }

        // If the document is not encrypted, simply return the file
        return Storage::download($document->file_path);
    }
}
