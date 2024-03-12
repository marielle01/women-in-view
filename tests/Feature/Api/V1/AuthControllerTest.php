<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthControllerTest extends SetUp
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
                    'role_id' => 0,
                ],
                404,
            ],
            [
                [
                    'email' => fake()->email,
                    'password' => 'testpassword123',
                    'firstName' => fake()->firstName,
                    'lastName' => fake()->lastName,
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
                    'currentPassword' => 'badCurrentPassword',
                    'password' => 'newPassword',
                    'confirmPassword' => 'newPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'BadMyNewPassword',
                    'confirmPassword' => 'BadMyNewPassword',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'newPassword#12',
                    'confirmPassword' => 'BadNewPassword#12rhjjkkkjhjhhj',
                ],
                404,
            ],
            [
                [
                    'currentPassword' => 'GoodCurrentPassword',
                    'password' => 'newPassword#12er',
                    'confirmPassword' => 'newPassword#12er',
                ],
                200,
            ],
        ];
    }

    public function test_user_login(): void
    {
        // Assert
        $response = $this->postJson('api/login', [
            'email' => config('app.user_email'),
            'password' => config('app.user_password'),
        ]);
        $response->assertStatus(200);
        $response->assertJson(fn (AssertableJson $json) => $json->has('user')
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
}
