<?php

namespace Tests\Feature\Api\V1;

use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthControllerTest extends Setup
{
    use RefreshDatabase;

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
                    'role_id' => 2,
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
                    'currentPassword' => 'badOldPassword',
                    'password' => 'newPassword',
                    'confirmPassword' => 'newPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'oldPassword12er',
                    'password' => 'BadMyNewPassword',
                    'confirmPassword' => 'BadMyNewPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'oldPassword12er',
                    'password' => 'newPassword12er',
                    'confirmPassword' => 'BadNewPassword#12rhj',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'oldPassword12er',
                    'password' => 'newPassword12er',
                    'confirmPassword' => 'newPassword12er',
                ],
                200,
            ],
        ];
    }

    public function test_admin_login(): void
    {
        // Assert
        $response = $this->postJson('api/login', [
            'email' => config('app.user_email'),
            'password' => config('app.user_password'),
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json
            ->has('user')
                ->where('user.id', 1)
                ->where('user.name', 'Admin')
                ->where('user.email', 'admin@gmail.com')
                ->where('user.role_id', 1)
                ->etc(),
        );
    }

    public function test_admin_login_failed(): void
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
        $user = User::factory(['password' => 'oldPassword12er'])->create();

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
