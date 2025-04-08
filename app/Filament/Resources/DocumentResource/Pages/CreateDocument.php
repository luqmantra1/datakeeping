<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    protected static string $resource = DocumentResource::class;

    public static function canAccess(array $parameters = []): bool

{
    return auth()->user()?->hasAnyRole(['Admin', 'CEO']);
}

}
