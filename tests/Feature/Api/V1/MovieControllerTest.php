<?php

namespace Tests\Feature\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MovieControllerTest extends Setup
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        // Create an admin
        $this->admin = User::factory()->create(['role_id' => 1]);

        // Create a subscriber
        $this->user = User::factory()->create(['role_id' => 2]);
    }

    public function test_admin_can_create_movie()
    {
        $this->actingAs($this->admin);

        $data = [
            'tmdb_id' => 27205,
            'original_title' => 'Inception',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => "Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life as payment for a task considered to be impossible: \"inception\", the implantation of another person\'s idea into a target\'s subconscious.",
            'release_date' => '2010-07-15',
            'rating' => 2
        ];

        $response = $this->postJson('/api/movies', $data);

        $response->assertStatus(200)
            ->assertJson($data);
    }

    public function test_regular_user_can_create_movie()
    {
        $this->actingAs($this->user);

        $data = [
            'tmdb_id' => 27205,
            'original_title' => 'Inception',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => "Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life as payment for a task considered to be impossible: \"inception\", the implantation of another person\'s idea into a target\'s subconscious.",
            'release_date' => 2010,
            'rating' => 2
        ];

        $response = $this->postJson('/api/movies', $data);

        $response->assertStatus(200);
    }

}
