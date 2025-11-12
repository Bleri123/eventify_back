<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            GenreSeeder::class,
            MoviesSeeder::class,
            MovieGenresSeeder::class,
            ShowroomsSeeder::class,
            SeatsSeeder::class,
            ScreeningsSeeder::class,
            BookingsSeeder::class,
            TicketsSeeder::class,
            PaymentsSeeder::class,
        ]);
    }
}
