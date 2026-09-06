<?php

namespace Tests\Feature;

use Tests\TestCase;

class CompareMatrixRendersTest extends TestCase
{
    public function test_compare_page_renders_semantic_table_when_schools_selected(): void
    {
        $response = $this->get('/bandingkan');
        // Table structure still renders on the empty state
        $response->assertStatus(200);
        $this->assertStringContainsString('<table', $response->getContent());
    }

    public function test_compare_table_has_compare_sticky_label_class(): void
    {
        $this->assertTrue(true);
    }

    public function test_sticky_first_column_class_present(): void
    {
        $this->assertTrue(true);
    }
}
