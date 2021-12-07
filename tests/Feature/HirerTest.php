<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class HirerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hirer_can_create_project()
    {
        User::factory()->create();
        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->postJson(route('hirer-add-project'), [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'tags' => 'tag tag tag',
        ], ['authorization' => 'Bearer ' . $response['data']['token']])->assertSee('successful');

        $this->assertDatabaseCount('projects', 1);
    }

    public function test_freelancer_can_not_create_project()
    {
        User::factory()->create(['role' => 'freelancer']);
        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->postJson(route('hirer-add-project'), [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'tags' => 'tag tag tag',
        ], ['authorization' => 'Bearer ' . $response['data']['token']])->assertSee('Unauthorized');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_create_project_validation()
    {
        User::factory()->create();
        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->postJson(route('hirer-add-project'), [], ['authorization' => 'Bearer ' . $response['data']['token']])
            ->assertSee(['The title field is required', 'The description field is required', 'The price field is required']);

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_show_all_a_hirers_projects()
    {
        Project::factory()->count(2)->create();
        Project::factory()->create(['user_id' => '1']);

        $token = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('hirer-all-project'), ['authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => [
                    'data' => [
                        ['id' => 1],
                        ['id' => 3]
                    ]]])
            ->assertJsonStructure([
                'data' => [
                    'data' => [
                        ['id', 'user', 'title', 'price', 'status'],
                        ['id', 'user', 'title', 'price', 'status']
                    ]]]);
    }

    public function test_show_one_a_hirers_project()
    {
        Project::factory()->count(2)->create();
        Project::factory()->create(['user_id' => '1']);

        $token = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('hirer-one-project', 3), ['authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => [
                    'id' => 3
                ]])
            ->assertJsonStructure([
                'data' => [
                    'id', 'user', 'title', 'price', 'status'
                ]]);
    }

    public function test_hirer_update_project()
    {
        Project::factory()->create();

        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->patchJson(route('hirer-update-project', 1), [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'tags' => 'tag tag tag',
        ], ['authorization' => 'Bearer ' . $response['data']['token']])->assertSee('updated successfully');

        $this->assertDatabaseHas('projects', [
            'title' => 'title',
            'description' => 'description',
            'price' => 'price',
            'tags' => 'tag tag tag'
        ]);
    }

    public function test_hirer_delete_project()
    {
        Project::factory()->create();

        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->deleteJson(route('hirer-delete-project', 1), [],
            ['authorization' => 'Bearer ' . $response['data']['token']])
            ->assertSee('deleted successfully');

        $this->assertDatabaseCount('projects', 0);
    }

    public function test_hirer_can_see_bids_of_one_project()
    {
        $user = User::factory()->create();
        $project = $user->projects()->create(Project::factory()->make()->toArray());
        $project->bidders()->attach(User::factory()->count(3)->create(['role' => 'freelancer']));

        $token = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('hirer-one-project-bids', 1), ['authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => [
                    'bidders' => [
                        ['id' => 3, 'role' => 'freelancer'],
                        ['id' => 4, 'role' => 'freelancer'],
                        ['id' => 5, 'role' => 'freelancer'],
                    ]
                ]
            ]);
    }

    public function test_hirer_can_accept_a_bid()
    {
        $user = User::factory()->create();
        $project = $user->projects()->create(Project::factory()->make()->toArray());
        $project->bidders()->attach(User::factory()->count(3)->create(['role' => 'freelancer']));

        $token = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ])['data']['token'];

        $this->postJson(route('hirer-accept-project-bid', ['project' => 1, 'user' => 5]), [],
            ['authorization' => 'Bearer ' . $token])
            ->assertSee('bid accepted');

        $this->assertDatabaseHas('projects',['freelancer_id'=>5, 'status' => 'done']);
    }
}
