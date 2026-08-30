<?php

namespace App\Domain\Onboarding;

enum DocumentKind: string
{
    case Kk = 'kk';
    case Akta = 'akta';
    case Rapor = 'rapor';
    case Photo = 'photo';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Kk => 'Kartu Keluarga',
            self::Akta => 'Akta Kelahiran',
            self::Rapor => 'Rapor Terakhir',
            self::Photo => 'Pas Foto',
            self::Other => 'Dokumen Lainnya',
        };
    }
}
