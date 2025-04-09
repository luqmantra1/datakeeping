<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SecureDownloadController extends Controller
{
    /**
     * Show the document.
     */
    public function show($id)
    {
        // Retrieve the document by ID
        $document = Document::findOrFail($id);

        // If the document is protected, ensure only Admin/CEO can download it
        if ($document->isProtected()) {
            if (!Auth::check() || (!Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('CEO'))) {
                abort(403, 'Unauthorized access. Only Admin or CEO can view this document.');
            }
        }

        // Check if the file exists in the storage
        if (!Storage::exists($document->file_path)) {
            abort(404, 'Document not found.');
        }

        // Return the document content (download the document)
        return Storage::download($document->file_path);
    }
}
