<?php

namespace App\Filament\Resources\MentorBookings;

use App\Filament\Resources\MentorBookings\Pages\CreateMentorBooking;
use App\Filament\Resources\MentorBookings\Pages\EditMentorBooking;
use App\Filament\Resources\MentorBookings\Pages\ListMentorBookings;
use App\Filament\Resources\MentorBookings\Schemas\MentorBookingForm;
use App\Filament\Resources\MentorBookings\Tables\MentorBookingsTable;
use App\Models\MentorBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class MentorBookingResource extends Resource
{
    protected static ?string $model = MentorBooking::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Booking Bimbel';

    protected static string|\UnitEnum|null $navigationGroup = 'Bimbel';

    protected static ?int $navigationSort = 2;

    protected static bool $shouldRegisterNavigation = true;

    public static function form(Schema $schema): Schema
    {
        return MentorBookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MentorBookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMentorBookings::route('/'),
            'create' => CreateMentorBooking::route('/create'),
            'edit' => EditMentorBooking::route('/{record}/edit'),
        ];
    }
}
