<?php

namespace Tests\Unit;

use App\Services\NearbySortService;
use PHPUnit\Framework\TestCase;

class NearbySortServiceTest extends TestCase
{
    public function test_it_returns_haversine_sql_expression(): void
    {
        $sql = NearbySortService::haversineRaw(-6.5971, 106.7990);

        $this->assertStringContainsString('6371 * acos', $sql);
        $this->assertStringContainsString('-6.5971', $sql);
        $this->assertStringContainsString('106.799', $sql);
    }
}
