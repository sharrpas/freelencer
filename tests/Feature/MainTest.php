<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MainTest extends TestCase
{
    use RefreshDatabase;


    public function test_show_all_projects()
    {
        Project::factory()->count(20)->create();
        Project::factory()->count(5)->create(['user_id' => '3']);

        $this->getJson(route('all-projects'))
            ->assertDontSee('description')
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        ['id', 'user', 'title'],
                        ['id', 'user', 'title'],
                        ['id', 'user', 'title'],
                    ],

                    'links' => [
                        'current_page',
                        'last_page',
                        'next_page',
                    ]
                ]
            ]);
    }

    public function test_show_one_project()
    {
        Project::factory()->count(20)->create();
        Project::factory()->count(5)->create(['user_id' => '3']);

        $this->getJson(route('one-project',25))
            ->assertSee(25)
            ->assertJsonStructure([
                'data' => [
                    'id', 'title', 'description'
                ]
            ]);
    }

}
