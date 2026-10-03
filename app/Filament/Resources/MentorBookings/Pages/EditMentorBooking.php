<?php

namespace App\Filament\Resources\MentorBookings\Pages;

use App\Filament\Resources\MentorBookings\MentorBookingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMentorBooking extends EditRecord
{
    protected static string $resource = MentorBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
