<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_username_is_required_to_login()
    {
        $this->postJson(route('login'))->assertSee("The username field is required");
    }


    public function test_password_is_required_to_login()
    {
        $this->postJson(route('login'))->assertSee("The password field is required");
    }


    public function test_validation_passed_if_username_and_password_insert()
    {
        User::factory()->create();
        $this->postJson(route('login'), [
            'username' => "notExist",
            'password' => "password",
        ])->assertSee("Username not found");
    }


    public function test_login_works()
    {
        User::factory()->create();

        $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ])
            ->assertStatus(200)
            ->assertSee('token')
            ->assertSee('demo')
            ->assertJson(['data' => ["user" => [
                "username" => "demo",
            ]]]);

        $this->assertDatabaseHas('users', [
            "username" => "demo",
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_login_did_not_works_if_the_username_and_password_is_incorrect()
    {
        User::factory()->create();

        $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'PASS'
        ])->assertSee('Password is wrong');
    }

    public function test_signup_works()
    {
        $this->postJson(route('signup'), [
            'name' => 'demo',
            'role' => 'freelancer',
            'username' => 'demo',
            'password' => 'password1',
            'password_confirmation' => 'password1',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'demo',
            'username' => 'demo',
        ]);
    }

    public function test_signup_validation()
    {
        User::factory()->create();
        $this->postJson(route('signup'), [
            'username' => 'demo',
            'password' => '1234',
            'password_confirmation' => '1230000000',
            'image' => '%%%',
        ])->assertSee(['The name field is required', 'The username has already been taken', 'The password confirmation does not match']);
    }


    public function test_logout_works()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('logout'), [], [
            'authorization' => 'Bearer ' . $response['data']['token']
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);

    }

    public function test_changepass_works()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('changePass'), [
            'old_pass' => 'password',
            'new_pass' => '1234',
            'new_pass_confirmation' => '1234'
        ], ['authorization' => 'Bearer ' . $response['data']['token']])->assertSee('password changed to 1234');
    }

    public function test_change_pass_error()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('login'), [
            'username' => 'demo',
            'password' => 'password'
        ]);
        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->postJson(route('changePass'), [
            'old_pass' => 'PASS',
            'new_pass' => '1234',
            'new_pass_confirmation' => '1234',
        ], ['authorization' => 'Bearer ' . $response['data']['token']])
            ->assertSee('token ERROR');

    }
}




