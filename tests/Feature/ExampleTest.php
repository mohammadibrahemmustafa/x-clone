<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_a_successful_response()
    {
        dd(config('database.connections.mysql.host'));
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
