<?php

namespace Tests\Unit;

use App\Services\ProjectionTitleAvailability;
use PHPUnit\Framework\TestCase;

class ProjectionTitleAvailabilityTest extends TestCase
{
    public function test_tagged_and_priced_title_is_selectable_without_warning(): void
    {
        $flags = ProjectionTitleAvailability::flags(true, '795.00');

        $this->assertTrue($flags['tagged']);
        $this->assertTrue($flags['priced']);
        $this->assertTrue($flags['selectable']);
        $this->assertSame('', $flags['warning']);
    }

    public function test_untagged_title_is_not_selectable(): void
    {
        $flags = ProjectionTitleAvailability::flags(false, '495.00');

        $this->assertFalse($flags['tagged']);
        $this->assertFalse($flags['selectable']);
        $this->assertSame(ProjectionTitleAvailability::NOT_TAGGED, $flags['warning']);
    }

    public function test_zero_price_is_a_warning_only(): void
    {
        $flags = ProjectionTitleAvailability::flags(true, '0.00');

        $this->assertTrue($flags['selectable']);
        $this->assertFalse($flags['priced']);
        $this->assertSame(ProjectionTitleAvailability::NO_PRICE, $flags['warning']);
    }

    public function test_untagged_and_unpriced_reports_both_messages(): void
    {
        $flags = ProjectionTitleAvailability::flags(false, 0);

        $this->assertFalse($flags['selectable']);
        $this->assertStringContainsString(ProjectionTitleAvailability::NOT_TAGGED, $flags['warning']);
        $this->assertStringContainsString(ProjectionTitleAvailability::NO_PRICE, $flags['warning']);
    }

    public function test_missing_price_is_treated_as_unpriced(): void
    {
        $flags = ProjectionTitleAvailability::flags(true, null);

        $this->assertFalse($flags['priced']);
        $this->assertTrue($flags['selectable']);
        $this->assertSame(ProjectionTitleAvailability::NO_PRICE, $flags['warning']);
    }
}
