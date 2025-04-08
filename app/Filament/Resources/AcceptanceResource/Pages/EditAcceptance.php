<?php

namespace App\Filament\Resources\AcceptanceResource\Pages;

use App\Filament\Resources\AcceptanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcceptance extends EditRecord
{
    protected static string $resource = AcceptanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
