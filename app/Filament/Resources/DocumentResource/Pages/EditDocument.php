<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditDocument extends EditRecord
{
    protected static string $resource = DocumentResource::class;

    // Customize the header actions, like Delete action
    protected function getHeaderActions(): array
    {
        return [
            // Custom Delete Action with Confirmation
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->action(function () {
                    // You can add some custom logic for when a document is deleted, like sending a notification
                    Notification::make()
                        ->title('Document Deleted')
                        ->success()
                        ->send();
                })
        ];
    }

    // Optional: Customize save logic, if you want to modify the data before saving
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // For example, you could add some custom logic here before saving, like adding a timestamp or modifying data.
        // You can return modified $data here if needed.
        return $data;
    }
}
