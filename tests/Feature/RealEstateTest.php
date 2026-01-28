<?php

namespace Tests\Feature;

use Tests\TestCase;

class RealEstateTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_homepage_loads(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
