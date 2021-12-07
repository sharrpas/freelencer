<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BidFactory extends Factory
{
//    protected $model = Bid::class;

    public function definition()
    {
        return [
            'user_id' => function() {
                User::factory();
            },
            'project_id' => function() {
                Project::factory();
            },
        ];
    }
}
