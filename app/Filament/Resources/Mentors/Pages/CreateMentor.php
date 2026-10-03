<?php

namespace App\Filament\Resources\Mentors\Pages;

use App\Filament\Resources\Mentors\MentorResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateMentor extends CreateRecord
{
    protected static string $resource = MentorResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::slug($data['name'] ?? '');

        return $data;
    }
}
