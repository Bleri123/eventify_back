<?php

namespace Database\Seeders;

use App\Models\movies;
use App\Models\screenings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ExtraMoviesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = Carbon::today(); // same as your frontend "today"

        $extraMovies = [
            [
                'title' => 'Inception',
                'description' => 'A skilled thief is given a chance at redemption if he can successfully perform inception.',
                'duration_minutes' => 148,
                'release_date' => '2010-07-16',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/inception.png',
            ],
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'duration_minutes' => 169,
                'release_date' => '2014-11-07',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/interstellar.png',
            ],
            [
                'title' => 'Oppenheimer',
                'description' => 'The story of J. Robert Oppenheimer and the development of the atomic bomb.',
                'duration_minutes' => 180,
                'release_date' => '2023-07-21',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/oppenheimer.png',
            ],
            [
                'title' => 'Dune',
                'description' => 'Paul Atreides leads nomadic tribes in a battle to control the desert planet Arrakis.',
                'duration_minutes' => 155,
                'release_date' => '2021-10-22',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/dune.png',
            ],
            [
                'title' => 'Dune: Part Two',
                'description' => 'Paul unites with Chani and the Fremen while seeking revenge against the conspirators.',
                'duration_minutes' => 166,
                'release_date' => '2024-03-01',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/dune-part-two.png',
            ],
            [
                'title' => 'Avatar',
                'description' => 'A paraplegic marine is dispatched to the moon Pandora on a unique mission.',
                'duration_minutes' => 162,
                'release_date' => '2009-12-18',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/avatar.png',
            ],
            [
                'title' => 'Avatar: The Way of Water',
                'description' => 'Jake Sully and Neytiri have formed a family and must protect their home.',
                'duration_minutes' => 192,
                'release_date' => '2022-12-16',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/avatar-way-of-water.png',
            ],
            [
                'title' => 'Mad Max: Fury Road',
                'description' => 'In a post-apocalyptic wasteland, Max teams up with a mysterious woman to flee a tyrant.',
                'duration_minutes' => 120,
                'release_date' => '2015-05-15',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/mad-max-fury-road.png',
            ],
            [
                'title' => 'John Wick',
                'description' => 'An ex-hitman comes out of retirement to track down gangsters that killed his dog.',
                'duration_minutes' => 101,
                'release_date' => '2014-10-24',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/john-wick.png',
            ],
            [
                'title' => 'John Wick: Chapter 2',
                'description' => 'John Wick is forced out of retirement again by a former associate.',
                'duration_minutes' => 122,
                'release_date' => '2017-02-10',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/john-wick-2.png',
            ],
            [
                'title' => 'Top Gun: Maverick',
                'description' => 'Maverick confronts his past while training a detachment of graduates.',
                'duration_minutes' => 130,
                'release_date' => '2022-05-27',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/top-gun-maverick.png',
            ],
            [
                'title' => 'Mission: Impossible – Fallout',
                'description' => 'Ethan Hunt and his IMF team race against time after a mission gone wrong.',
                'duration_minutes' => 147,
                'release_date' => '2018-07-27',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/mi-fallout.png',
            ],
            [
                'title' => 'Guardians of the Galaxy',
                'description' => 'A group of intergalactic criminals must pull together to stop a fanatic.',
                'duration_minutes' => 121,
                'release_date' => '2014-08-01',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/guardians-1.png',
            ],
            [
                'title' => 'Guardians of the Galaxy Vol. 2',
                'description' => 'The Guardians struggle to keep their newfound family together.',
                'duration_minutes' => 136,
                'release_date' => '2017-05-05',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/guardians-2.png',
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'description' => 'Spider-Man turns to Doctor Strange for help with his secret identity.',
                'duration_minutes' => 148,
                'release_date' => '2021-12-17',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/spiderman-no-way-home.png',
            ],
            [
                'title' => 'The Batman',
                'description' => 'Batman uncovers corruption in Gotham while pursuing the Riddler.',
                'duration_minutes' => 176,
                'release_date' => '2022-03-04',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/the-batman.png',
            ],
            [
                'title' => 'Joker',
                'description' => 'A failed comedian descends into madness and becomes the Joker.',
                'duration_minutes' => 122,
                'release_date' => '2019-10-04',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/joker.png',
            ],
            [
                'title' => 'Whiplash',
                'description' => 'A promising young drummer enrolls at a cutthroat music conservatory.',
                'duration_minutes' => 107,
                'release_date' => '2014-10-10',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/whiplash.png',
            ],
            [
                'title' => 'La La Land',
                'description' => 'A jazz pianist falls for an aspiring actress in Los Angeles.',
                'duration_minutes' => 128,
                'release_date' => '2016-12-09',
                'movie_language' => 'English',
                'status' => 'now_showing',
                'poster_url' => 'posters/la-la-land.png',
            ],
            [
                'title' => 'Parasite',
                'description' => 'Greed and class discrimination threaten the newly formed symbiotic relationship between two families.',
                'duration_minutes' => 132,
                'release_date' => '2019-10-11',
                'movie_language' => 'Korean',
                'status' => 'now_showing',
                'poster_url' => 'posters/parasite.png',
            ],
        ];

        foreach ($extraMovies as $index => $movieData) {
            /** @var \App\Models\movies $movie */
            $movie = movies::create(array_merge($movieData, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            // Create 3 screenings for TODAY for each new movie
            $times = ['14:30:00', '17:30:00', '20:30:00'];

            foreach ($times as $time) {
                screenings::create([
                    'movie_id'    => $movie->id,
                    'showroom_id' => 1,
                    'start_time'  => $today->copy()->setTimeFromTimeString($time),
                    'base_price'  => 6.50,
                    'status'      => 'on_sale',
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }
}