<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages\CreateSchool;
use App\Filament\Resources\SchoolResource\Pages\EditSchool;
use App\Filament\Resources\SchoolResource\Pages\ListSchools;
use App\Filament\Resources\SchoolResource\Pages\ViewSchool;
use App\Models\Facility;
use App\Models\Program;
use App\Models\School;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Sekolah';

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Informasi Dasar')
                ->description('Nama, jenis, dan lokasi sekolah')
                ->icon('heroicon-m-identification')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Sekolah')
                        ->placeholder('Contoh: Pondok Pesanren Al-Munawwir')
                        ->required(),
                    Forms\Components\Hidden::make('slug'),
                    Forms\Components\Select::make('type')
                        ->label('Tipe Sekolah')
                        ->options([
                            'Pesantren Modern' => 'Pesantren Modern',
                            'Pesantren Salaf' => 'Pesantren Salaf',
                            'SDIT' => 'SDIT (Sekolah Dasar Islam Terpadu)',
                            'SMPIT' => 'SMPIT (Sekolah Menengah Pertama Islam Terpadu)',
                            'SMAIT' => 'SMAIT (Sekolah Menengah Atas Islam Terpadu)',
                            'MTs' => 'MTs (Madrasah Tsanawiyah)',
                            'MA' => 'MA (Madrasah Aliyah)',
                        ])
                        ->required()
                        ->searchable(),
                    Forms\Components\TextInput::make('city')
                        ->label('Kota/Kabupaten')
                        ->placeholder('Contoh: Yogyakarta')
                        ->required(),
                    Forms\Components\TextInput::make('province')
                        ->label('Provinsi')
                        ->placeholder('Contoh: DI Yogyakarta')
                        ->required(),
                    Forms\Components\Textarea::make('address')
                        ->label('Alamat Lengkap')
                        ->placeholder('Jl. Raya Solo-Yogya, Km. 14, Kalasan, Sleman, Yogyakarta 55571')
                        ->rows(2),
                ]),

            Section::make('Jenjang Pendidikan')
                ->description('Pilih jenjang yang tersedia di sekolah ini')
                ->icon('heroicon-m-academic-cap')
                ->schema([
                    Forms\Components\CheckboxList::make('jenjang')
                        ->label('Jenjang yang Tersedia')
                        ->options([
                            'RA' => 'Raudhatul Athfal (PAUD)',
                            'TK' => 'TK Islam',
                            'SD' => 'SD/MI',
                            'SMP' => 'SMP/MTs',
                            'SMA' => 'SMA/MA',
                            'SMK' => 'SMK/MAK',
                            'Pesantren' => 'Tingkat Pesantren',
                        ]),
                ]),

            Section::make('Deskripsi Sekolah')
                ->description('Tuliskan deskripsi lengkap tentang sekolah')
                ->icon('heroicon-m-document-text')
                ->schema([
                    Forms\Components\Textarea::make('short_desc')
                        ->label('Deskripsi Singkat')
                        ->placeholder('Deskripsi singkat 2-3 kalimat untuk kartu sekolah dan preview')
                        ->maxLength(300)
                        ->rows(2),
                    Forms\Components\RichEditor::make('description')
                        ->label('Deskripsi Lengkap')
                        ->placeholder('Tuliskan sejarah, visi misi, keunikan, dan keunggulan sekolah...')
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->fileAttachmentsDirectory('schools/attachments'),
                ]),

            Section::make('Tag & Label')
                ->description('Tambahkan tag untuk kategorisasi')
                ->icon('heroicon-m-tag')
                ->schema([
                    Forms\Components\TagsInput::make('tags')
                        ->label('Tag Sekolah')
                        ->placeholder('Tekan enter untuk menambah tag')
                        ->suggestions([
                            'Tahfidz Al-Quran',
                            'Bahasa Arab',
                            'Bahasa Inggris',
                            'Komputer',
                            'Olahraga',
                            'Seni',
                            'Pramuka',
                            'Palang Merah Remaja',
                            'Kitab Kuning',
                            'Boardung',
                            'Ekstrakurikuler',
                            'Mutqin',
                        ]),
                    Forms\Components\TextInput::make('badge')
                        ->label('Badge Spesial')
                        ->placeholder('Contoh: "Baru 2024" atau "Favorit"'),
                    Forms\Components\TextInput::make('accreditation')
                        ->label('Akreditasi')
                        ->placeholder('A')
                        ->maxLength(5),
                ]),

            Section::make('Foto & Galeri')
                ->description('Unggah foto utama dan galeri sekolah')
                ->icon('heroicon-m-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Foto Utama Sekolah')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                        ->directory('schools/images')
                        ->maxSize(5120)
                        ->helperText('Foto yang akan muncul di kartu sekolah dan halaman detail. Ukuran optimal 1200x800px. Maks 5MB'),
                    Forms\Components\FileUpload::make('photos')
                        ->label('Galeri Foto')
                        ->image()
                        ->multiple()
                        ->maxFiles(20)
                        ->reorderable()
                        ->directory('schools/gallery')
                        ->maxSize(5120)
                        ->helperText('Tambahkan foto-foto kegiatan dan fasilitas sekolah. Maks 20 foto, 5MB per foto'),
                ]),

            Section::make('Lokasi & Peta')
                ->description('Koordinat GPS untuk menampilkan sekolah di peta')
                ->icon('heroicon-m-map-pin')
                ->schema([
                    Forms\Components\TextInput::make('latitude')
                        ->label('Latitude')
                        ->placeholder('-7.762839')
                        ->helperText('Koordinat lintang. Contoh: -7.762839')
                        ->numeric()
                        ->step(0.0000001),
                    Forms\Components\TextInput::make('longitude')
                        ->label('Longitude')
                        ->placeholder('110.370910')
                        ->helperText('Koordinat bujur. Contoh: 110.370910')
                        ->numeric()
                        ->step(0.0000001),
                    Forms\Components\Placeholder::make('map_helper')
                        ->label('Cara Mendapatkan Koordinat')
                        ->content('Buka Google Maps → Klik lokasi sekolah → Klik kanan pada titik lokasi → Pilih angka pertama adalah Latitude, angka kedua adalah Longitude'),
                ]),

            Section::make('Kontak & WhatsApp')
                ->description('Informasi kontak sekolah')
                ->icon('heroicon-m-phone')
                ->schema([
                    Forms\Components\TextInput::make('whatsapp_e164')
                        ->label('Nomor WhatsApp')
                        ->placeholder('6281234567890')
                        ->helperText('Format: kode negara + nomor (tanpa tanda +). Contoh: 6281234567890')
                        ->tel(),
                    Forms\Components\TextInput::make('whatsapp_label')
                        ->label('Label WhatsApp')
                        ->placeholder('Admin PPDB'),
                ]),

            Section::make('Program & Fasilitas')
                ->description('Pilih atau tambahkan program dan fasilitas baru')
                ->icon('heroicon-m-presentation-chart-bar')
                ->schema([
                    Forms\Components\CheckboxList::make('programs')
                        ->label('Program Unggulan')
                        ->relationship('programs', 'name')
                        ->bulkToggleable(),
                    Forms\Components\Select::make('new_programs')
                        ->label('Tambah Program Baru')
                        ->multiple()
                        ->options(Program::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Program')
                                ->required(),
                            Forms\Components\TextInput::make('slug')
                                ->label('Slug')
                                ->required(),
                            Forms\Components\Textarea::make('description')
                                ->label('Deskripsi'),
                            Forms\Components\TextInput::make('icon')
                                ->label('Icon')
                                ->placeholder('heroicon-m-star'),
                        ])
                        ->createOptionUsing(function (array $data): array {
                            $program = Program::create($data);

                            return [$program->id];
                        }),
                    Forms\Components\CheckboxList::make('facilities')
                        ->label('Fasilitas Sekolah')
                        ->relationship('facilities', 'name')
                        ->bulkToggleable(),
                    Forms\Components\Select::make('new_facilities')
                        ->label('Tambah Fasilitas Baru')
                        ->multiple()
                        ->options(Facility::pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->createOptionForm([
                            Forms\Components\TextInput::make('name')
                                ->label('Nama Fasilitas')
                                ->required(),
                            Forms\Components\TextInput::make('icon')
                                ->label('Icon')
                                ->placeholder('heroicon-m-check'),
                        ])
                        ->createOptionUsing(function (array $data): array {
                            $facility = Facility::create($data);

                            return [$facility->id];
                        }),
                ]),

            Section::make('Prestasi & Penghargaan')
                ->description('Tambahkan prestasi sekolah')
                ->icon('heroicon-m-trophy')
                ->schema([
                    Forms\Components\Repeater::make('achievements')
                        ->label('Prestasi')
                        ->relationship()
                        ->schema([
                            Forms\Components\TextInput::make('title')
                                ->label('Judul Prestasi')
                                ->placeholder('Contoh: Juara 1 OSN Matematika Tingkat Provinsi')
                                ->required(),
                            Forms\Components\TextInput::make('year')
                                ->label('Tahun')
                                ->placeholder('2024')
                                ->numeric()
                                ->minValue(1900)
                                ->maxValue(2100),
                        ])
                        ->defaultItems(0)
                        ->addActionLabel('Tambah Prestasi'),
                ]),

            Section::make('Statistik Sekolah')
                ->description('Data statistik untuk ditampilkan di profil sekolah')
                ->icon('heroicon-m-chart-bar')
                ->schema([
                    Forms\Components\TextInput::make('students_count')
                        ->label('Jumlah Siswa')
                        ->placeholder('500')
                        ->numeric()
                        ->minValue(0),
                    Forms\Components\TextInput::make('teacher_ratio')
                        ->label('Rasio Guru:Siswa')
                        ->placeholder('1:15'),
                    Forms\Components\TextInput::make('founded_year')
                        ->label('Tahun Berdiri')
                        ->placeholder('1995')
                        ->numeric()
                        ->minValue(1800)
                        ->maxValue(2100),
                    Forms\Components\KeyValue::make('alumni_stats')
                        ->label('Statistik Alumni')
                        ->keyLabel('Kategori')
                        ->valueLabel('Jumlah')
                        ->addActionLabel('Tambah Baris')
                        ->helperText('Contoh: Lulusan PTN = 150, Lulusan最好 = 75'),
                ]),

            Section::make('Biaya Pendidikan (IDR)')
                ->description('Rincian biaya pendaftaran dan bulanan')
                ->icon('heroicon-m-currency-dollar')
                ->schema([
                    Forms\Components\TextInput::make('uang_pangkal')
                        ->label('Uang Pangkal')
                        ->placeholder('5000000')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Biaya pendaftaran awal (satu kali)'),
                    Forms\Components\TextInput::make('spp_monthly')
                        ->label('SPP Bulanan')
                        ->placeholder('1500000')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Biaya SPP per bulan'),
                    Forms\Components\TextInput::make('asrama_monthly')
                        ->label('Biaya Asrama / Bulan')
                        ->placeholder('1000000')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Biaya asrama per bulan (kosongkan jika tidak ada)'),
                    Forms\Components\TextInput::make('seragam_fee')
                        ->label('Biaya Seragam')
                        ->placeholder('1500000')
                        ->numeric()
                        ->prefix('Rp')
                        ->helperText('Biaya seragam (satu kali)'),
                    Forms\Components\TextInput::make('ekskul_fee')
                        ->label('Biaya Ekstrakurikuler / Tahun')
                        ->placeholder('500000')
                        ->numeric()
                        ->prefix('Rp'),
                    Forms\Components\TextInput::make('study_tour_fee')
                        ->label('Biaya Study Tour / Tahun')
                        ->placeholder('2000000')
                        ->numeric()
                        ->prefix('Rp'),
                ]),

            Section::make('Status & Penayangan')
                ->description('Pengaturan publikasi sekolah')
                ->icon('heroicon-m-eye')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Dipublikasikan')
                        ->onColor('success')
                        ->offColor('gray')
                        ->helperText('Jika aktif, sekolah akan muncul di halaman publik'),
                    Forms\Components\Toggle::make('is_verified')
                        ->label('Terverifikasi')
                        ->onColor('success')
                        ->offColor('gray')
                        ->helperText('Tandakan jika data sekolah sudah diverifikasi'),
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Sekolah Unggulan')
                        ->onColor('warning')
                        ->offColor('gray')
                        ->helperText('Sekolah ini akan ditampilkan di halaman utama'),
                    Forms\Components\Toggle::make('is_boarding')
                        ->label('Berasrama')
                        ->onColor('success')
                        ->offColor('gray')
                        ->helperText('Apakah sekolah memiliki fasilitas asrama?'),
                    Forms\Components\Toggle::make('registration_open')
                        ->label('Pendaftaran Dibuka')
                        ->onColor('success')
                        ->offColor('gray')
                        ->helperText('Jika aktif, tombol "Daftar" akan muncul di halaman sekolah'),
                    Forms\Components\Select::make('admission_status')
                        ->label('Status Pendaftaran')
                        ->options([
                            'open' => 'Terbuka',
                            'limited' => 'Terbatas',
                            'closed' => 'Ditutup',
                            'coming_soon' => 'Segera Buka',
                        ])
                        ->default('open'),
                    Forms\Components\Textarea::make('admission_notes')
                        ->label('Catatan Pendaftaran')
                        ->placeholder('Gelombang 1: 1 Jan - 28 Feb 2025. Gelombang 2: 1 Mar - 30 Apr 2025...')
                        ->rows(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Tipe Sekolah')
                    ->options([
                        'Pesantren Modern' => 'Pesantren Modern',
                        'Pesantren Salaf' => 'Pesantren Salaf',
                        'SDIT' => 'SDIT',
                        'SMPIT' => 'SMPIT',
                        'SMAIT' => 'SMAIT',
                        'MTs' => 'MTs',
                        'MA' => 'MA',
                    ])
                    ->searchable(),
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Publikasi'),
                Tables\Filters\TernaryFilter::make('is_verified')
                    ->label('Verifikasi'),
                Tables\Filters\TernaryFilter::make('is_boarding')
                    ->label('Asrama'),
            ])
            ->actions([
                Actions\ViewAction::make()
                    ->label('Lihat')
                    ->labeledFrom('md')
                    ->tooltip(' '),
                Actions\EditAction::make()
                    ->label('Edit')
                    ->labeledFrom('md')
                    ->tooltip(' '),
                Actions\DeleteAction::make()
                    ->label('Hapus')
                    ->labeledFrom('md')
                    ->tooltip(' '),
            ])
            ->bulkActions([
                Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => ListSchools::route('/'),
            'create' => CreateSchool::route('/create'),
            'view' => ViewSchool::route('/{record}'),
            'edit' => EditSchool::route('/{record}/edit'),
        ];
    }
}
