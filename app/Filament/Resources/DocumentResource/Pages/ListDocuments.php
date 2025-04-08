<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        // Allow all roles (Admin, CEO, Manager, Team Member) to access the documents page
        return auth()->user()?->hasAnyRole(['Admin', 'CEO', 'Manager', 'Team Member']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}


