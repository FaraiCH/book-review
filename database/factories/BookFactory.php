<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = Faker::create();
        return [
            'title' => $faker->sentence,
            'author' => $faker->name,
            'created_at' => $faker->dateTimeBetween('-2 years'),
            'updated_at' => $faker->dateTimeBetween('created_at', 'now'),
        ];
    }
}
