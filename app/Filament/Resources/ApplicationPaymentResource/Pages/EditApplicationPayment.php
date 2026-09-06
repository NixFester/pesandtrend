<?php

namespace App\Filament\Resources\ApplicationPaymentResource\Pages;

use App\Filament\Resources\ApplicationPaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicationPayment extends EditRecord
{
    protected static string $resource = ApplicationPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
