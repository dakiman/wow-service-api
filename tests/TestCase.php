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

	// public function validationRequired($type, $route = '/api/register', $data = false)
    // {
    //     $data = $data ? $data : $this->getUser();
    //     unset($data[$type]);
    //     $response = $this->json('POST', $route, $data);
    //     $response
    //         ->assertStatus(400)
    //         ->assertJsonValidationErrors($type)
    //         ->assertJsonFragment(['The ' . $type . ' field is required.']);
    // }
}
