<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * Class TimeApiTest
 *
 * This class contains feature tests for the Time API.
 * It extends the base TestCase class provided by the Laravel framework.
 * The tests in this class are designed to verify the functionality and
 * correctness of the Time API endpoints.
 *
 * @package Tests\Feature
 */
class TimeApiTest extends TestCase
{
    public function test_requires_token()
    {
        $this->getJson('/api/time')->assertStatus(401);
    }

    public function test_returns_server_time()
    {
        $token = '9faa37b23f350c516e3589e60083d10cd368df01';

        $this->withHeaders(['Authorization' => "Bearer $token"])
            ->getJson('/api/time')
            ->assertStatus(200)
            ->assertJsonStructure(['server_time']);
    }
}
