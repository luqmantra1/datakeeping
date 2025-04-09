<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;

class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Download Document')
                ->color('primary')
                ->url(fn () => route('download.document', $this->record->id)) // Ensure this is the correct route for download
                ->visible(fn () => Auth::user()->hasAnyRole(['Admin', 'CEO'])),

            Action::make('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending' && Auth::user()->hasAnyRole(['Admin', 'CEO']))
                ->action(fn () => $this->approveDocument()),

            Action::make('Reject')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => $this->record->status === 'pending' && Auth::user()->hasAnyRole(['Admin', 'CEO']))
                ->action(fn () => $this->rejectDocument()),
        ];
    }

    protected function approveDocument()
    {
        $document = $this->record;
        $document->status = 'approved';
        $document->approved_by = Auth::id();
        $document->save();

        // Redirect back to the document list
        $this->redirect(DocumentResource::getUrl('index'));
    }

    protected function rejectDocument()
    {
        $document = $this->record;
        $document->status = 'rejected';
        $document->approved_by = Auth::id();
        $document->save();

        // Redirect back to the document list
        $this->redirect(DocumentResource::getUrl('index'));
    }
}
