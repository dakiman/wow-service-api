<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\EasyValidate;
use App\User;

class UserTest extends TestCase
{
    use DatabaseTransactions, EasyValidate;

    public function testUserShouldRegister()
    {
        $user = $this->getUser();
        $response = $this->json('POST', '/api/register', $user);
        unset($user['password']);
        $response
            ->assertStatus(201)
            ->assertJson(['user' => $user]);
    }

    public function testLoginShouldReturnUser()
    {
        //create the password first so we can send it as plaintext in the request
        $password = str_random(10);
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);
        $response = $this->json('POST', '/api/login', ['email' => $user->email, 'password' => $password]);
        $response
            ->assertStatus(200)
            //assert that the response has the following json somewhere
            ->assertJsonFragment($user->toArray());
    }

    public function testShouldReturnAuthenticatedUser()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->get('/api/user');
        $response
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function testRegistrationEmailMustBeValid()
    {
        $user = $this->getUser();
        $user['email'] = str_random(10);
        $response = $this->json('POST', '/api/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(["The email must be a valid email address."]);
    }

    // public function testNameMaxLength()
    // {
    // 	$user = $this->getUser();
    // 	$user['name'] = str_random(31);
    // 	$response = $this->json('POST', '/api/register', $user);
    //     $response
    //         ->assertStatus(400)
    //         ->assertJsonValidationErrors('name')
    //         ->assertJsonFragment(["The name may not be greater than 30 characters."]);
    // }

    public function testRegistrationPasswordRequired()
    {
        $this->validationRequired('password');
    }

    public function testRegistrationNameRequired()
    {
        $this->validationRequired('name');
    }

    public function testRegistrationEmailRequired()
    {
        $this->validationRequired('email');
    }

    public function testNameMaxLength()
    {
        $this->validationMaxLength('name', 30);
    }

    public function testNameMinLength()
    {
        $this->validationMinLength('name', 2);
    }

    public function testPasswordMinLength()
    {
        $this->validationMinLength('password', 8);
    }
}
