<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieControllerTest extends SetUp
{
    use RefreshDatabase;

    /**
     *
     */
    public function test_view_any_movies(): void
    {
        $this->setUserPermission()

        $response->assertStatus(200);
    }
}
