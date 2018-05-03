<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $faker;

    //construct the class with the faker object
    public function __construct()
    {
        parent::__construct();
        $this->faker = \Faker\Factory::create();
    }

    public function getUser()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => str_random(10),
        ];
    }

    public function validationRequired($type, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        $user = $this->getUser();
        unset($user[$type]);
        $response = $this->json('POST', $route, $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' field is required.']);
    }

    public function validationMaxLength($type, $limit, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        $user[$type] = str_random($limit + 1);
        $response = $this->json('POST', $route, $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' may not be greater than ' . $limit . ' characters.']);
    }

    public function validationMinLength($type, $limit, $route = '/api/register', $data = false)
    {
        $data = $data ? $data : $this->getUser();
        $user[$type] = str_random($limit - 1);
        $response = $this->json('POST', $route, $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' must be at least ' . $limit . ' characters.']);
    }
}
