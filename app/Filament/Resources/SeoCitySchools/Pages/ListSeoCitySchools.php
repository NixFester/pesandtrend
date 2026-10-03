<?php

namespace App\Filament\Resources\SeoCitySchools\Pages;

use App\Filament\Resources\SeoCitySchools\SeoCitySchoolResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoCitySchools extends ListRecords
{
    protected static string $resource = SeoCitySchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
