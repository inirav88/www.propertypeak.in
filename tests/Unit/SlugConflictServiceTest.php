<?php

namespace Tests\Unit;

use Botble\Developer\Services\SlugConflictService;
use PHPUnit\Framework\TestCase;

class SlugConflictServiceTest extends TestCase
{
    public function test_reserved_keyword_is_blocked(): void
    {
        $service = new SlugConflictService();

        $this->assertTrue($service->isReserved('admin'));
        $this->assertTrue($service->isReserved('account'));
        $this->assertTrue($service->isReserved('password'));
    }

    public function test_slug_is_normalized(): void
    {
        $service = new SlugConflictService();

        $this->assertSame('abc-builders', $service->normalize('ABC Builders'));
    }
}
