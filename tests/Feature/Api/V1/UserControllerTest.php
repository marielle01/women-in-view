<?php

namespace Tests\Feature\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

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
                    'password' => 'short',
                ],
                404,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => fake()->email,
                    'password' => 'goodPassword12',
                ],
                200,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function updateUserProvider(): array
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
                    'name' => 'Jane Doe',
                    'email' => 'janeDoe@gmail.com',
                ],
                200,
            ],
        ];
    }


    public function test_view_any_users()
    {
        $this->setUserPermissions(['viewAnyUsers']);
        Sanctum::actingAs($this->user, ['*']);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        $response = $this->getJson('api/users');

        if ($response->status() === 200) {
            $response->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 5)
                    ->has('data.2', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('name', $user1->name)
                        ->where('email', $user1->email)
                        ->where('role_id', $user1->role_id)
                        ->etc(),
                    )
                    ->has('data.3', fn (AssertableJson $json) => $json
                        ->where('id', $user2->id)
                        ->where('name', $user2->name)
                        ->where('email', $user2->email)
                        ->where('role_id', $user2->role_id)
                        ->etc(),
                    )
                    ->has('data.4', fn (AssertableJson $json) => $json
                        ->where('id', $user3->id)
                        ->where('name', $user3->name)
                        ->where('email', $user3->email)
                        ->where('role_id', $user3->role_id)
                        ->etc(),
                    )
                    ->etc()
            );
        }
    }

    public function test_view_any_users_denied()
    {
        $this->setUserPermissions([]);

        $user = User::factory(['role_id' => 2])->create()->save();
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->getJson('api/users');
        $response->assertStatus(403);
    }

    public function test_show_user_subscriber()
    {
        $this->setUserPermissions(['viewUsers']);
        Sanctum::actingAs($this->user, ['*']);

        $user = User::factory(['role_id' => 2])->create();

        $response = $this->getJson('api/users/'.$user->id);

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email)
                    ->where('role_id', $user->role_id)
                    ->etc(),
                )
                ->etc(),
        );
    }

    public function test_show_user()
    {
        $this->setUserPermissions(['viewUsers']);
        Sanctum::actingAs($this->user, ['*']);

        $user = User::factory(['role_id' => 1])->create();

        $response = $this->getJson('api/users/'.$user->id);

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email)
                    ->where('role_id', $user->role_id)
                    ->etc(),
                )
                ->etc(),
        );
    }


    /**
     * @dataProvider updateUserProvider
     * @return void
     */
    public function test_update_user()
    {
        $this->setUserPermissions(['updateUsers']);

        $user = User::factory()->create();
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->putJson('api/users/'.$user->id);

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', $user->id)
                    ->where('name', $user->name)
                    ->where('email', $user->email)
                    ->where('role_id', $user->role_id)
                    ->etc(),
                )
                ->etc(),
        );
    }

    /**
     * @dataProvider addUserProvider
     *
     * @return void
     */
    public function test_add_user($data, $status)
    {
        $this->setUserPermissions(['createUsers']);

        Sanctum::actingAs($this->user, ['*']);

        $response = $this->postJson('api/users', $data);

        $response->assertStatus($status);

        if ($status === 200) {
            $response->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('name', $data['name'])
                        ->where('email', $data['email'])
                        ->has('role_id')
                        ->etc(),
                    )
                    ->etc(),
            );
        }
    }

    public function test_delete_user()
    {
        $this->setUserPermissions(['deleteUsers']);

        $user = User::factory()->create();

        $response = $this->deleteJson('api/users/'.$user->id);

        $response->assertStatus(200);

        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('success', true)
                ->where('message', 'User deleted')
                ->etc(),
        );

        $this->assertModelMissing($user);
    }
}
