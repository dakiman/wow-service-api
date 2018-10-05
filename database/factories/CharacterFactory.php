<?php

use Faker\Generator as Faker;

$factory->define(App\Character::class, function (Faker $faker) {
    return [
		'name' => str_random(10),
		'realm' => str_random(10),
		'class' => random_int(1, 12),
		'thumbnail' => str_random(12),
		'battlegroup' => str_random(12),
		'faction' => random_int(0, 1),
		'gender' => random_int(0, 1),
		'race' => random_int(1, 22),
		'level' => random_int(0, 110),
		'totalHonorableKills' => random_int(0, 100000),
		'achievementPoints' => random_int(0, 15000),
		'user_id' => 1
    ];
});
