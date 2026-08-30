<?php

namespace App\Filament\Resources\OnboardingApplicationResource\Pages;

use App\Filament\Resources\OnboardingApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplication extends EditRecord
{
    protected static string $resource = OnboardingApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
