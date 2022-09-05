<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        DB::table('urls')->insert([
            ['name' => 'https://google.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://vk.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://laravel.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://youtube.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://github.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://ubuntu.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://apple.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://yahoo.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://bing.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://microsoft.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://mozilla.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://php.net', 'created_at' => $faker->dateTime()],
            ['name' => 'https://stackoverflow.com', 'created_at' => $faker->dateTime()],
            ['name' => 'https://hh.ru', 'created_at' => $faker->dateTime()],
            ['name' => 'https://auto.ru', 'created_at' => $faker->dateTime()],
            ['name' => 'https://kinopoisk.ru', 'created_at' => $faker->dateTime()],
            ['name' => 'https://drive2.ru', 'created_at' => $faker->dateTime()],
            ['name' => 'https://formula1.com', 'created_at' => $faker->dateTime()]
        ]);
    }
}
