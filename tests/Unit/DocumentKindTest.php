<?php

namespace Tests\Unit;

use App\Domain\Onboarding\DocumentKind;
use PHPUnit\Framework\TestCase;

class DocumentKindTest extends TestCase
{
    public function test_it_returns_human_readable_label(): void
    {
        $this->assertEquals('Kartu Keluarga', DocumentKind::Kk->label());
        $this->assertEquals('Akta Kelahiran', DocumentKind::Akta->label());
        $this->assertEquals('Rapor Terakhir', DocumentKind::Rapor->label());
        $this->assertEquals('Pas Foto', DocumentKind::Photo->label());
        $this->assertEquals('Dokumen Lainnya', DocumentKind::Other->label());
    }
}
