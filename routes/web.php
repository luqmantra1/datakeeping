<?php

use App\Http\Controllers\SecureDownloadController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Document;
use Filament\Facades\Filament;
use App\Filament\Resources\DocumentResource\Pages\ViewDocument;

// Custom Route for Document Download
Route::get('/download/document/{id}', function ($id) {
    $document = Document::findOrFail($id);

    // Check if the document is encrypted
    if ($document->encrypted) {
        // Get the encrypted file content
        $encryptedContent = Storage::get($document->file_path);

        // Decrypt the file content using AES-256-CBC
        $key = base64_decode(env('ENCRYPTION_KEY'));  // Get your encryption key from .env
        $iv = substr(hash('sha256', 'iv-secret'), 0, 16); // The IV for AES-256-CBC, ensure it's 16 bytes

        // Decrypt the content
        $decryptedContent = openssl_decrypt($encryptedContent, 'AES-256-CBC', $key, 0, $iv);

        // Create a temporary file to store the decrypted content
        $tempFile = tempnam(sys_get_temp_dir(), 'decrypted_');
        file_put_contents($tempFile, $decryptedContent);

        // Return the decrypted content as a downloadable file
        return response()->download($tempFile, basename($document->file_path));
    }

    // If the document is not encrypted, simply download the file
    return Storage::download($document->file_path);
})->name('download.document');

// Custom Route for Testing Role-Based Access
Route::get('/test-role', function () {
    $user = Auth::user();

    if ($user && $user->hasRole('Admin')) {
        return 'You are Admin';
    }

    return 'You are not Admin';
});

// Route for Viewing Document (Filament Resource Page)
Route::get('/documents/{document}/view', ViewDocument::class)->name('documents.view');

// Filament Resources and Routes will be automatically registered via the Filament Service Provider

