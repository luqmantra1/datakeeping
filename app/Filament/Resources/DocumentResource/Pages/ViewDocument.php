<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use App\Notifications\DocumentApprovalStatus;


class ViewDocument extends ViewRecord
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action to Download the document: visible to everyone if document is not encrypted
            Action::make('Download Document')
                ->color('primary')
                ->url(fn () => route('download.document', $this->record->id)) // This is where it uses the named route
                ->visible(fn () => 
                    !$this->record->encrypted || Auth::user()->hasRole('Admin') || Auth::user()->hasRole('CEO')
                )
                ->openUrlInNewTab(),


            // Action to Approve the document: only visible to Admin and CEO if status is 'pending'
            Action::make('Approve')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn () => 
                    $this->record->status === 'pending' && Auth::user()->hasAnyRole(['Admin', 'CEO'])
                )
                ->action(fn () => $this->approveDocument()),

            // Action to Reject the document: only visible to Admin and CEO if status is 'pending'
            Action::make('Reject')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn () => 
                    $this->record->status === 'pending' && Auth::user()->hasAnyRole(['Admin', 'CEO'])
                )
                ->action(fn () => $this->rejectDocument()),
        ];
    }

    // Approve document action
    protected function approveDocument()
    {
        $document = $this->record;

        $document->status = 'approved';
        $document->approved_by = Auth::id();
        $document->save();

        // Notify the uploader about the approval
        if ($document->uploader) {
            $document->uploader->notify(new DocumentApprovalStatus($document, 'approved'));
        }

        Notification::make()
            ->title('Document approved successfully.')
            ->success()
            ->send();

        $this->redirect(DocumentResource::getUrl('index'));
    }

    // Reject document action
    protected function rejectDocument()
    {
        $document = $this->record;

        $document->status = 'rejected';
        $document->approved_by = Auth::id();
        $document->save();

        // Notify the uploader about the rejection
        if ($document->uploader) {
            $document->uploader->notify(new DocumentApprovalStatus($document, 'rejected'));
        }

        Notification::make()
            ->title('Document rejected.')
            ->danger()
            ->send();

        $this->redirect(DocumentResource::getUrl('index'));
    }
}


