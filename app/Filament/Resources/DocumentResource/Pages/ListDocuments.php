<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    // This method checks if the user can create a document (based on their role)
    protected function canCreate(): bool
    {
        // Only Admin and CEO can create documents
        return auth()->user()?->hasAnyRole(['Admin', 'CEO']);
    }

    protected function getHeaderActions(): array
    {
        return [
            // Only show 'Create Document' button if the user can create documents
            Actions\CreateAction::make()->visible(fn () => $this->canCreate()),
        ];
    }
}
