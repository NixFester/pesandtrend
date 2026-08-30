<?php

namespace App\Filament\Pages;

use App\Services\SchoolsImportService;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Livewire\WithFileUploads;

class ImportSchools extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-up-tray';

    protected static ?string $navigationLabel = 'Import Sekolah';

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 5;

    protected string $view = 'filament.pages.import-schools';

    public $csvFile = null;

    public function getView(): string
    {
        return 'filament.pages.import-schools';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\FileUpload::make('csvFile')
                ->label('File CSV')
                ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel', '.csv'])
                ->required()
                ->disk('local')
                ->directory('imports'),
        ]);
    }

    public function import(): void
    {
        $this->validate(['csvFile' => 'required']);

        $path = storage_path('app/'.$this->csvFile);

        if (! file_exists($path)) {
            Notification::make()
                ->title('File tidak ditemukan')
                ->danger()
                ->send();

            return;
        }

        $service = app(SchoolsImportService::class);
        $result = $service->importFromCsv($path);

        Notification::make()
            ->title("Import selesai: {$result['imported']} berhasil, {$result['skipped']} dilewati.")
            ->success()
            ->send();

        $this->csvFile = null;
    }
}
