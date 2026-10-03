<?php

namespace App\Filament\Resources\SeoCitySchools\Pages;

use App\Filament\Resources\SeoCitySchools\SeoCitySchoolResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSeoCitySchool extends EditRecord
{
    protected static string $resource = SeoCitySchoolResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
