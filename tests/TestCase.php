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

}
