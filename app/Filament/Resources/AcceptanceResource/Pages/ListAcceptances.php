<?php

namespace App\Filament\Resources\AcceptanceResource\Pages;

use App\Filament\Resources\AcceptanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAcceptances extends ListRecords
{
    protected static string $resource = AcceptanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
