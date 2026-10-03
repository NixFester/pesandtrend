<?php

namespace App\Filament\Resources\SeoCities\Pages;

use App\Filament\Resources\SeoCities\SeoCityResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoCities extends ListRecords
{
    protected static string $resource = SeoCityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
