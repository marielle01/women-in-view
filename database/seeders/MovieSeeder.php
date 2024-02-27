<?php

namespace Database\Seeders;

use App\Models\Api\V1\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $movie1 = Movie::factory([
            'tmdb_id' => 915935,
            'original_title' => 'Anatomie d\'une chute',
            'poster_path' => '/kQs6keheMwCxJxrzV83VUwFtHkB.jpg',
            'backdrop_path' => '/fGe1ej335XbqN1j9teoDpofpbLX.jpg',
            'overview' => 'A woman is suspected of her husband’s murder, and their blind son faces a moral dilemma as the sole witness.',
            'release_date' => '2023-08-23',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $movie2 = Movie::factory([
                'tmdb_id' => 335977,
                'original_title' => 'Indiana Jones and the Dial of Destiny',
                'poster_path' => '/Af4bXE63pVsb2FtbW8uYIyPBadD.jpg',
                'backdrop_path' => '/35z8hWuzfFUZQaYog8E9LsXW3iI.jpg',
                'overview' => 'Finding himself in a new era, and approaching retirement, Indy wrestles with fitting into a world that seems to have outgrown him. But as the tentacles of an all-too-familiar evil return in the form of an old rival, Indy must don his hat and pick up his whip once more to make sure an ancient and powerful artifact doesn\'t fall into the wrong hands.',
                'release_date' => '2023-06-28',
                'rating' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ])->create();

        $movie3 = Movie::factory([
                'tmdb_id' => 724495,
                'original_title' => 'The Woman King',
                'poster_path' => '/438QXt1E3WJWb3PqNniK0tAE5c1.jpg',
                'backdrop_path' => '/xTsERrOCW15OIYl5aPX7Jbj38wu.jpg',
                'overview' => 'The story of the Agojie, the all-female unit of warriors who protected the African Kingdom of Dahomey in the 1800s with skills and a fierceness unlike anything the world has ever seen, and General Nanisca as she trains the next generation of recruits and readies them for battle against an enemy determined to destroy their way of life.',
                'release_date' => '2022-09-16',
                'rating' => 3,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ])->create();

        $movie4 = Movie::factory([
            'tmdb_id' => 1541,
            'original_title' => 'Thelma & Louise',
            'poster_path' => '/gQSUVGR80RVHxJywtwXm2qa1ebi.jpg',
            'backdrop_path' => '/2rzNmNKBwqu77ExgP0eEsNvQama.jpg',
            'overview' => 'Whilst on a short weekend getaway, Louise shoots a man who had tried to rape Thelma. Due to the incriminating circumstances, they make a run for it and thus a cross country chase ensues for the two fugitives. Along the way, both women rediscover the strength of their friendship and surprising aspects of their personalities and self-strengths in the trying times.',
            'release_date' => '1991-05-24',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $movie5 = Movie::factory([
            'tmdb_id' => 346698,
            'original_title' => 'Barbie',
            'poster_path' => '/iuFNMS8U5cb6xfzi51Dbkovj7vM.jpg',
            'backdrop_path' => '/ctMserH8g2SeOAnCw5gFjdQF8mo.jpg',
            'overview' => 'Barbie and Ken are having the time of their lives in the colorful and seemingly perfect world of Barbie Land. However, when they get a chance to go to the real world, they soon discover the joys and perils of living among humans.',
            'release_date' => '2023-07-19',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $movie6 = Movie::factory([
            'tmdb_id' => 873,
            'original_title' => 'The Color Purple',
            'poster_path' => '/pcXRsQQcH9Iw9fU5t6TgnxqcroC.jpg',
            'backdrop_path' => '/rhMpAPvoy1beXd8gA6CvYoCSztj.jpg',
            'overview' => 'An epic tale spanning forty years in the life of Celie, an African-American woman living in the South who survives incredible abuse and bigotry. After Celie\'s abusive father marries her off to the equally debasing "Mister" Albert Johnson, things go from bad to worse, leaving Celie to find companionship anywhere she can. She perseveres, holding on to her dream of one day being reunited with her sister in Africa.',
            'release_date' => '1985-12-18',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $movie7 = Movie::factory([
            'tmdb_id' => 466420,
            'original_title' => 'Killers of the Flower Moon',
            'poster_path' => '/dB6Krk806zeqd0YNp2ngQ9zXteH.jpg',
            'backdrop_path' => '/acvE3RWjDLgvbL2RtcyzkrsAyNV.jpg',
            'overview' => 'When oil is discovered in 1920s Oklahoma under Osage Nation land, the Osage people are murdered one by one—until the FBI steps in to unravel the mystery.',
            'release_date' => '2023-10-18',
            'rating' => 1,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ])->create();

        $movie8 = Movie::factory([
            'tmdb_id' => 297762,
            'original_title' => 'Wonder Woman',
            'poster_path' => '/imekS7f1OuHyUP2LAiTEM0zBzUz.jpg',
            'backdrop_path' => '/6iUNJZymJBMXXriQyFZfLAKnjO6.jpg',
            'overview' => 'An Amazon princess comes to the world of Man in the grips of the First World War to confront the forces of evil and bring an end to human conflict.',
            'release_date' => '2017-05-30',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ])->create();

        $movie9 = Movie::factory([
            'tmdb_id' => 301502,
            'original_title' => 'Blonde',
            'poster_path' => '/mEeHqtnWOR44vLCutEFku2WK6ou.jpg',
            'backdrop_path' => '/c3TfoiUES6INii849Yhd8ZYagLy.jpg',
            'overview' => 'From her volatile childhood as Norma Jeane, through her rise to stardom and romantic entanglements, this reimagined fictional portrait of Hollywood legend Marilyn Monroe blurs the lines of fact and fiction to explore the widening split between her public and private selves.',
            'release_date' => '2022-09-16',
            'rating' => 2,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ])->create();

        $movie10 = Movie::factory([
            'tmdb_id' => 978035,
            'original_title' => 'Bâtiment 5',
            'poster_path' => '/q0b163rtX1qc0TVPkONel42yOJQ.jpg',
            'backdrop_path' => '/sJhqhyDy0XsPErmW0AYh8HQ9PB7.jpg',
            'overview' => 'This charts the journey of a fierce young woman, Habi, and a budding new mayor, Pierre, crossing paths in an underprivileged suburb on the outskirts of Paris. Habi, a native of the suburb who is involved in social orgs helping locals, becomes a political figure. Pierre, meanwhile, is a former doctor who takes the city’s reins after the mayor’s death and sets off to follow his agenda.',
            'release_date' => '2023-12-06',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ])->create();

        $movie11 = Movie::factory([
            'tmdb_id' => 24,
            'original_title' => 'Kill Bill: Vol. 1',
            'poster_path' => '/v7TaX8kXMXs5yFFGR41guUDNcnB.jpg',
            'backdrop_path' => '/lVy5Zqcty2NfemqKYbVJfdg44rK.jpg',
            'overview' => 'An assassin is shot by her ruthless employer, Bill, and other members of their assassination circle – but she lives to plot her vengeance.',
            'release_date' => '2003-10-10',
            'rating' => 3,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ])->create();

        $movie12 = Movie::factory([
            'tmdb_id' => 872585,
            'original_title' => 'Oppenheimer',
            'poster_path' => '/ptpr0kGAckfQkJeJIt8st5dglvd.jpg',
            'backdrop_path' => '/ycnO0cjsAROSGJKuMODgRtWsHQw.jpg',
            'overview' => 'The story of J. Robert Oppenheimer\'s role in the development of the atomic bomb during World War II.',
            'release_date' => '2023-07-19',
            'rating' => 2,
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ])->create();
    }
}
