<?php

namespace Tests\Feature\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;


    public static function addUserRegister(): array
    {
        return [
            [
                [
                    'name' => '',
                    'email' => fake()->email,
                    'password' => fake()->password(8),
                ],
                500,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => 'jane.com',
                    'password' => fake()->password(8),
                ],
                500,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => fake()->email,
                    'password' => '',
                ],
                500,
            ],
            [
                [
                    'name' => fake()->name(),
                    'email' => fake()->email,
                    'password' => fake()->password(8),
                ],
                200,
            ],
        ];
    }

    /**
     * @return array[]
     */
    public static function changePasswordProvider(): array
    {
        return [
            [
                [
                    'currentPassword' => 'badCurrentPassword',
                    'password' => 'newPassword',
                    'confirmPassword' => 'newPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'BadPass',
                    'confirmPassword' => 'BadPass',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'newPassword',
                    'confirmPassword' => 'BadNewPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'newPassword12er',
                    'confirmPassword' => 'newPassword12er',
                ],
                200,
            ],
        ];
    }


    /**
     * @dataProvider addUserRegister
     *
     * @return void
     */
    public function test_user_register($data, $status)
    {

        $response = $this->postJson('api/register', $data);

        $response->assertStatus($status);

        if ($status === 200) {
            $response->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('user', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('name', $data['name'])
                        ->where('email', $data['email'])
                        ->has('role_id')
                        ->etc(),
                    )
                    ->has('token')
                    ->etc(),
            );
        }
    }

    public function test_user_login(): void
    {
        // Assert
        $response = $this->postJson('api/login', [
            'email' => config('app.user_email'),
            'password' => config('app.user_password'),
        ]);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('user')
            ->where('user.id', 1)
            ->where('user.name', 'Admin')
            ->where('user.email', 'admin@gmail.com')
            ->where('user.role_id', 1)
            ->etc(),
        );
    }

    public function test_user_login_failed(): void
    {
        // Assert
        $response = $this->postJson('api/login', [
            'email' => config('app.user_email'),
            'password' => 'bad_password',
        ]);
        $response->assertStatus(401);
        $response->assertJson(
            fn (AssertableJson $json) => $json
                ->where('status', false)
                ->etc(),
        );
    }

    /**
     * @param array $data
     * @param int $status
     * @return void
     * @dataProvider changePasswordProvider
     */
    public function test_change_password(array $data, int $status): void
    {
        $user = User::factory(['password' => 'GoodCurrentPassword'])->create();

        $this->postJson('api/login', [
            'email' => $user->email,
            'password' => 'GoodCurrentPassword',
        ]);

        $response = $this->postJson('api/change-password', $data);
        $response->assertStatus($status);

        if ($status === 200) {
            $response->assertJson(
                fn(AssertableJson $json) =>$json
                ->where('success', true)
                ->where('message', 'Password has changed')
                ->etc()
            );
        }
    }

}
