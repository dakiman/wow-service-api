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

    public function validationRequired($type = null)
    {
        $user = $this->getUser();
        unset($user[$type]);
        $response = $this->json('POST', '/api/register', $user);
        $response
                ->assertStatus(400)
                ->assertJsonValidationErrors($type)
                ->assertJsonFragment(['The ' . $type . ' field is required.']);
	}

	public function validationMaxLength($type = null, $limit = null) {
		$user = $this->getUser();
		$user[$type] = str_random($limit + 1);
		$response = $this->json('POST', '/api/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' may not be greater than 30 characters.']);
	}

	public function validationMinLength($type = null, $limit = null)
	{
		$user = $this->getUser();
		$user[$type] = str_random($limit - 1);
		$response = $this->json('POST', '/api/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors($type)
            ->assertJsonFragment(['The ' . $type . ' must be at least ' . $limit . ' characters.']);
	}
}
