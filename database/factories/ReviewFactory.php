<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as Faker;

class ReviewFactory extends Factory
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
            'book_id' => 3,
            'review' => $faker->paragraph,
            'rating' => $faker->numberBetween(1, 5),
            'created_at' => $faker->dateTimeBetween('-2 years'),
            'updated_at' => $faker->dateTimeBetween('created_at', 'now'),
        ];
    }

    public function good(){
        return $this->state(function (array $attributes){
            $faker = Faker::create();
            return [
                'rating' => $faker->numberBetween(4,5)
            ];
        });
    }

    public function bad(){
        return $this->state(function (array $attributes){
            $faker = Faker::create();
            return [
                'rating' => $faker->numberBetween(1,3)
            ];
        });
    }
}
