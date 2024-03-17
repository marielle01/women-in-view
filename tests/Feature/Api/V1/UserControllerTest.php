<?php

namespace Tests\Feature\Api\V1;

use App\Models\Api\V1\Permission;
use App\Models\Api\V1\Role;
use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;



    public function setUp(): void
    {
        parent::setUp();

        // Create an admin
        $this->admin = User::factory()->create(['role_id' => 1]);

        // Create a subscriber
        $this->subscriber = User::factory()->create(['role_id' => 2]);
    }

    public static function addUserProvider(): array
    {
        return [
            [
                [
                    'name' => '',
                    'email' => fake()->email,
                    'password' => 'goodPassword',
                ],
                404,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => 'jane.com',
                    'password' => 'goodPassword',
                ],
                404,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => fake()->email,
                    'password' => 'mypass',
                ],
                404,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => fake()->email,
                    'password' => 'goodPassword',
                ],
                200,
            ],
        ];
    }

    /**
     * @return array[]
     */
    protected static function updateUserProvider(): array
    {
        return [
            [
                [
                    'email' => 'janeDoe.com',
                ],
                404,
            ],
            [
                [
                    'email' => fake()->email,
                    'name' => fake()->name(),
                ],
                200,
            ],
        ];
    }


    public function test_view_any_users()
    {
        $this->setUserPermissions(['viewAnyUsers']);
        Sanctum::actingAs($this->admin, ['*']);

        $users = User::factory()->count(3)->create(['role_id' => 2]);

        $response = $this->getJson('api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role_id',
                    ],
                ],
            ])
            ->assertJsonFragment([
                'id' => $users[0]->id,
                'name' => $users[0]->name,
                'email' => $users[0]->email,
                'role_id' => 2,
            ])
            ->assertJsonFragment([
                'id' => $users[1]->id,
                'name' => $users[1]->name,
                'email' => $users[1]->email,
                'role_id' => 2,
            ])
            ->assertJsonFragment([
                'id' => $users[2]->id,
                'name' => $users[2]->name,
                'email' => $users[2]->email,
                'role_id' => 2,
            ]);
    }


}
