<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function() {
                return User::factory();
            },
            'title' => $this->faker->title(),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(100, 100000),
            'tags' => $this->faker->firstName . ' ' . $this->faker->firstName,
            'status' => 'open',
        ];
    }
}
