<?php

namespace App\Filament\Resources\Campaigns\Tables;

use App\Models\Campaign;
use App\Models\School;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CampaignsTable
{
    public static function configure(Table $table): Table
    {
        $toolbarActions = [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];

        // Add bulk generate for development
        if (app()->environment('local', 'development')) {
            $toolbarActions[] = Action::make('generateBulk')
                ->label('Generate 5 Random')
                ->icon('heroicon-o-sparkles')
                ->color('info')
                ->action(function () {
                    $schools = School::published()->get();
                    if ($schools->isEmpty()) {
                        return;
                    }

                    $categories = array_keys(Campaign::categories());
                    $titles = [
                        'Renovasi Asrama Putri',
                        'Beasiswa Santri Berprestasi',
                        'Renovasi Masjid',
                        'Peralatan Laboratorium',
                        'Bantuan Medis untuk Santri',
                    ];

                    for ($i = 0; $i < 5; $i++) {
                        $school = $schools->random();
                        $title = $titles[array_rand($titles)].' - '.$school->name;

                        Campaign::create([
                            'school_id' => $school->id,
                            'title' => $title,
                            'slug' => Str::slug($title).'-'.Str::random(4),
                            'category' => $categories[array_rand($categories)],
                            'description' => 'Campaign untuk meningkatkan kualitas pendidikan di '.$school->name.'.',
                            'target_amount' => fake()->randomElement([10000000, 25000000, 50000000, 75000000, 100000000]),
                            'current_amount' => fake()->numberBetween(0, 10000000),
                            'start_date' => now(),
                            'end_date' => now()->addMonths(fake()->numberBetween(1, 6)),
                            'status' => 'active',
                            'is_featured' => fake()->boolean(30),
                            'image' => 'images/masjid/renov'.fake()->numberBetween(1, 3).'.jpg',
                        ]);
                    }

                    return redirect()->back();
                });
        }

        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions($toolbarActions)
            ->defaultSort('created_at', 'desc');
    }
}
