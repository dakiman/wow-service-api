<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		// $this->call(UsersTableSeeder::class);
		$this->call(RealmSeeder::class);
		\App\User::create([
			'name' => 'daki',
			'email' => 'daki@daki.com',
			'password' => bcrypt('password')
		]);
    }
}
