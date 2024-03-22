<?php

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Setup extends TestCase
{
    protected static $Token;
    protected array $headers;
    protected array $defaultPermissions = [
        ['id' => 1, 'name' => 'viewAnyMovies'],
        ['id' => 2, 'name' => 'viewMovies'],
        ['id' => 3, 'name' => 'createMovies'],
        ['id' => 4, 'name' => 'updateMovies'],
        ['id' => 5, 'name' => 'deleteMovies'],
        ['id' => 6, 'name' => 'viewAnyUsers'],
        ['id' => 7, 'name' => 'viewUsers'],
        ['id' => 8, 'name' => 'createUsers'],
        ['id' => 9, 'name' => 'updateUsers'],
        ['id' => 10, 'name' => 'deleteUsers'],
        ['id' => 11, 'name' => 'viewAnyMovies'],
        ['id' => 12, 'name' => 'viewMovies'],
        ['id' => 13, 'name' => 'createMovies'],
        ['id' => 14, 'name' => 'updateMovies'],
        ['id' => 15, 'name' => 'deleteMovies'],
        ['id' => 16, 'name' => 'viewUsers'],
        ['id' => 17, 'name' => 'createUsers'],
        ['id' => 18, 'name' => 'updateUsers'],
    ];

    protected array $permissions;

    public function setUp(): void
    {
        $this->permissions = $this->defaultPermissions;

        parent::setUp();

        $response = $this->withHeaders([
            'REFERER' => 'http://localhost:3000',
        ])->postJson('api/login', ['email' => 'admin@gmail.com', 'password' => 'password123']);

        if ($response->status() == 200) {
            self::$Token = $response->json('data');
        }
        $this->withoutExceptionHandling();
    }
}
