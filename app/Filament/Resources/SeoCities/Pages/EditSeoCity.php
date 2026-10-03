<?php

namespace App\Filament\Resources\SeoCities\Pages;

use App\Filament\Resources\SeoCities\SeoCityResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeoCity extends EditRecord
{
    protected static string $resource = SeoCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
