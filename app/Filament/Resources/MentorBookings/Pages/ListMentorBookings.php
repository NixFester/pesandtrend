<?php

namespace App\Filament\Resources\MentorBookings\Pages;

use App\Filament\Resources\MentorBookings\MentorBookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMentorBookings extends ListRecords
{
    protected static string $resource = MentorBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
