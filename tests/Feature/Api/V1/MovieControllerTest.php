<?php

namespace Tests\Feature\Api\V1;

use App\Http\Controllers\Api\V1\MovieController;
use App\Models\Api\V1\Movie;
use App\Models\Api\V1\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Mockery\MockInterface;
use Tests\TestCase;

class MovieControllerTest extends TestCase
{
    use RefreshDatabase;


    public static function addMovieProvider(): array
    {
        return [
            [
                [
                    'tmdb_id' => '',
                ],
                404,
            ],
            [
                [
                    'rating' => '',
                ],
                404,
            ],

            [
                [
                    'tmdb_id' => 27205,
                    'original_title' => 'Inception',
                    'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
                    'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
                    'overview' => "Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life as payment for a task considered to be impossible: \"inception\", the implantation of another person\'s idea into a target\'s subconscious.",
                    'release_date' => '2010-07-15',
                    'rating' => 2,
                ],
                200,
            ],
        ];
    }

    public static function updateMovieProvider(): array
    {
        return [
            [
                [
                    'rating' => 'rating',
                ],
                404,
            ],
            [
                [
                    'tmdb_id' => 27205,
                    'original_title' => 'Inception',
                    'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
                    'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
                    'overview' => "Cobb, a skilled thief who commits corporate espionage by infiltrating the subconscious of his targets is offered a chance to regain his old life as payment for a task considered to be impossible: \"inception\", the implantation of another person\'s idea into a target\'s subconscious.",
                    'release_date' => '2010-07-15',
                    'rating' => 1,
                ],
                200,
            ],
        ];
    }


    public function test_view_any_movies()
    {
        $this->setUserPermissions(['viewAnyMovies']);
        Sanctum::actingAs($this->user, ['*']);

        $movie1 = Movie::factory()->create();
        $movie2 = Movie::factory()->create();
        $movie3 = Movie::factory()->create();
        $movie4 = Movie::factory()->create();

        $response = $this->getJson('api/movies');

        if ($response->status() === 200) {
            $response->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('data', 4)
                    ->has('data.0', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('tmdb_id', $movie1->tmdb_id)
                        ->where('original_title', $movie1->original_title)
                        ->where('poster_path', $movie1->poster_path)
                        ->etc(),
                    )
                    ->has('data.1', fn (AssertableJson $json) => $json
                        ->where('id', $movie2->id)
                        ->where('tmdb_id', $movie2->tmdb_id)
                        ->where('original_title', $movie2->original_title)
                        ->where('poster_path', $movie2->poster_path)
                        ->etc(),
                    )
                    ->has('data.2', fn (AssertableJson $json) => $json
                        ->where('id', $movie3->id)
                        ->where('tmdb_id', $movie3->tmdb_id)
                        ->where('original_title', $movie3->original_title)
                        ->where('poster_path', $movie3->poster_path)
                        ->etc(),
                    )
                    ->has('data.3', fn (AssertableJson $json) => $json
                        ->where('id', $movie4->id)
                        ->where('tmdb_id', $movie4->tmdb_id)
                        ->where('original_title', $movie4->original_title)
                        ->where('poster_path', $movie4->poster_path)
                        ->etc(),
                    )
                    ->etc()
            );
        }
    }


    public function test_show_movie()
    {
        $this->setUserPermissions(['viewMovies']);
        Sanctum::actingAs($this->user, ['*']);

        $movie = Movie::factory()->create();

        $response = $this->getJson('api/movies/'.$movie->id);

        $response->assertStatus(200);

        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', $movie->id)
                    ->where('tmdb_id', $movie->tmdb_id)
                    ->where('original_title', $movie->original_title)
                    ->where('poster_path', $movie->poster_path)
                    ->etc(),
                )
                ->etc(),
        );
    }


    /**
     * @dataProvider addMovieProvider
     *
     */
    public function test_add_movie($data, $status)
    {
        $this->setUserPermissions(['createMovies']);
        Sanctum::actingAs($this->user, ['*']);

        $response = $this->postJson('/api/movies', $data);

        $response->assertStatus($status);

        if ($status === 200) {
            $response->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->where('tmdb_id', $data['tmdb_id'] )
                        ->where('original_title', $data['original_title'] )
                        ->where('poster_path', $data['poster_path'] )
                        ->where('backdrop_path', $data['backdrop_path'] )
                        ->where('overview', $data['overview'] )
                        ->where('release_date', $data['release_date'] )
                        ->where('rating', $data['rating'] )
                        ->has('user_id')
                        ->etc(),
                    )
                    ->etc(),
            );
        }
    }


    /**
     * @dataProvider updateMovieProvider
     * @return void
     */
    public function test_update_rating($data, $status)
    {
        $this->setUserPermissions(['updateMovies']);

        Sanctum::actingAs($this->user, ['*']);

        $movie = Movie::factory()->create();
        $movie->rating = 3;
        $movie->save();


        $response = $this->putJson('/api/movies' .$movie->id, $data);

        $response->assertStatus($status);

        if ($response->status() === 200) {
            $response->assertJson(
                fn(AssertableJson $json) => $json
                    ->has('data', fn (AssertableJson $json) => $json
                        ->where('id', $movie->id)
                        ->where('tmdb_id', $movie->tmdb_id)
                        ->where('original_title', $movie->original_title)
                        ->where('poster_path', $movie->poster_path)
                        ->where('backdrop_path', $movie->backdrop_path)
                        ->where('overview', $movie->overview)
                        ->where('release_date', $movie->release_date)
                        ->where('rating', 1)
                        ->etc(),
                    )
                    ->etc(),
            );
        }

    }


    public function test_delete_movie()
    {
        $this->setUserPermissions(['deleteMovies']);

        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $movie = Movie::factory()->create();

        $response = $this->deleteJson('/api/movies' .$movie->id);

        $response->assertStatus(200);
        $this->assertModelMissing($movie);
    }

    public function test_search_movie()
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        /*$this->partialMock(MovieController::class, function (MockInterface $mock) {
            $mock->shouldReceive('searchMovie');
        });*/

        $movie1 = Movie::factory([
                'tmdb_id' => 1,
                'original_title' => 'Spider',
                'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
                'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
                'overview' => "Cobb, a skilled thief who commits ...",
                'release_date' => '2010-07-15',
                'rating' => 2
            ])->create();

        $movie2 = Movie::factory([
            'tmdb_id' => 2,
            'original_title' => 'Spider man',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => "Cobb, a skilled thief who commits ...",
            'release_date' => '2010-07-15',
            'rating' => 2
        ])->create();

        $movie3 = Movie::factory([
            'tmdb_id' => 3,
            'original_title' => 'Man and gold',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => "Cobb, a skilled thief who commits ...",
            'release_date' => '2010-07-15',
            'rating' => 1
        ])->create();

        $movie4 = Movie::factory([
            'tmdb_id' => 4,
            'original_title' => 'Good girl',
            'poster_path' => '/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
            'backdrop_path' => '/JeGkRdNsOuMrgwBdtB0hp763MU.jpg',
            'overview' => "Cobb, a skilled thief who commits ...",
            'release_date' => '2010-07-15',
            'rating' => 3
        ])->create();

        $response = $this->getJson('api/search-movies/spider');

        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', 2)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->where('tmdb_id', $movie1->tmdb_id)
                    ->where('original_title', $movie1->tmdb_id)
                    ->where('poster_path', $movie1->poster_path)
                    ->where('backdrop_path', $movie1->backdrop_path)
                    ->where('overview', $movie1->overview)
                    ->where('release_date', $movie1->release_date)
                    ->where('rating', $movie1->rating)
                    ->etc()
                )
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->where('tmdb_id', $movie2->tmdb_id)
                    ->where('original_title', $movie2->tmdb_id)
                    ->where('poster_path', $movie2->poster_path)
                    ->where('backdrop_path', $movie2->backdrop_path)
                    ->where('overview', $movie2->overview)
                    ->where('release_date', $movie2->release_date)
                    ->where('rating', $movie2->rating)
                    ->etc()
                )
                ->etc()
        );

        $response = $this->getJson('api/search-movies/man');

        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', 2)
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->where('tmdb_id', $movie2->tmdb_id)
                    ->where('original_title', $movie2->tmdb_id)
                    ->where('poster_path', $movie2->poster_path)
                    ->where('backdrop_path', $movie2->backdrop_path)
                    ->where('overview', $movie2->overview)
                    ->where('release_date', $movie2->release_date)
                    ->where('rating', $movie2->rating)
                    ->etc()
                )
                ->has('data.2', fn (AssertableJson $json) => $json
                    ->where('tmdb_id', $movie3->tmdb_id)
                    ->where('original_title', $movie3->tmdb_id)
                    ->where('poster_path', $movie3->poster_path)
                    ->where('backdrop_path', $movie3->backdrop_path)
                    ->where('overview', $movie3->overview)
                    ->where('release_date', $movie3->release_date)
                    ->where('rating', $movie3->rating)
                    ->etc()
                )
                ->etc()
        );

        $response = $this->getJson('api/search-movies/girly');

        $response->assertStatus(200);
        $response->assertJson(
            fn(AssertableJson $json) => $json
                ->has('data', 1)
                ->has('data.3', fn (AssertableJson $json) => $json
                    ->where('tmdb_id', $movie4->tmdb_id)
                    ->where('original_title', $movie4->tmdb_id)
                    ->where('poster_path', $movie4->poster_path)
                    ->where('backdrop_path', $movie4->backdrop_path)
                    ->where('overview', $movie4->overview)
                    ->where('release_date', $movie4->release_date)
                    ->where('rating', $movie4->rating)
                    ->etc()
                )
                ->etc()
        );
    }

}
