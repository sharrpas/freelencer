<?php

namespace Tests\Feature;

use App\Models\Bid;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FreelacerTest extends TestCase
{
    use RefreshDatabase;

    public function test_freelancer_can_see_his_projects()
    {
        /** @var User $hirer */
        $hirer = User::factory()->create(['username' => 'hirer']);

        /** @var User $freelancer */
        $freelancer = User::factory()->create(['role' => 'freelancer', 'username' => 'freelancer']);

        /** @var Project $project */
        $project = $hirer->projects()->create(Project::factory()->make()->toArray());
        $project->setStatus('done')->addFreelancer($freelancer)->execute();

        /** @var Project $project1 */
        $project1 = $hirer->projects()->create(Project::factory()->make()->toArray());
        $project1->setStatus('done')->addFreelancer($freelancer)->execute();

        $token = $this->postJson(route('login'), [
            'username' => 'freelancer',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('freelancer-all-project'), ['authorization' => 'Bearer ' . $token])
            ->assertJson([
                'data' => [
                    'data' => [
                        ['id' => 1, 'status' => 'done'],
                        ['id' => 2, 'status' => 'done']
                    ]
                ]
            ]);
    }

    public function test_freelancer_can_see_one_project()
    {
        /** @var User $hirer */
        $hirer = User::factory()->create(['username' => 'hirer']);

        /** @var User $freelancer */
        $freelancer = User::factory()->create(['role' => 'freelancer', 'username' => 'freelancer']);

        /** @var Project $project */
        $project = $hirer->projects()->create(Project::factory()->make()->toArray());
        $project->setStatus('done')->addFreelancer($freelancer)->execute();

        /** @var Project $project1 */
        $project1 = $hirer->projects()->create(Project::factory()->make()->toArray());
        $project1->setStatus('done')->addFreelancer($freelancer)->execute();

        $token = $this->postJson(route('login'), [
            'username' => 'freelancer',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('freelancer-one-project', ['project' => 2]), ['authorization' => 'Bearer ' . $token])
            ->assertSee('description')
            ->assertJson([
                'data' => [
                    'id' => 2,
                    'freelancer' => [
                        'id' => $freelancer->id,
                    ]
                ]
            ]);
    }

    public function test_show_all_freelancers_bid_to_him()
    {
        $hirer = User::factory()->create(['username' => 'hirer']);
        $freelancer = User::factory()->create(['role' => 'freelancer', 'username' => 'freelancer']);
        /** @var Project $project */
        $project = $hirer->projects()->create(Project::factory()->make()->toArray());
        $project->bidders()->attach($freelancer);

        $token = $this->postJson(route('login'), [
            'username' => 'freelancer',
            'password' => 'password'
        ])['data']['token'];

        $this->getJson(route('freelancer-all-bids'), ['authorization' => 'Bearer ' . $token])
            ->assertSee('open')
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'bids' => [
                        ['id', 'price', 'status'],
                    ]
                ]
            ]);
    }

    public function test_freelancer_can_add_bid_to_a_project()
    {
        $hirer = User::factory()->create(['username' => 'hirer']);
        $freelancer = User::factory()->create(['role' => 'freelancer', 'username' => 'freelancer']);
        /** @var Project $project */
        $project = $hirer->projects()->create(Project::factory()->make()->toArray());

        $token = $this->postJson(route('login'), [
            'username' => 'freelancer',
            'password' => 'password'
        ])['data']['token'];

        $this->postJson(route('freelancer-add-bid',['project' => $project->id]),[],['authorization' => 'Bearer ' . $token])
            ->assertSee('bid saved');

        $this->assertDatabaseCount('bids',1);
    }
}
