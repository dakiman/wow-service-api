<?php

use Illuminate\Database\Seeder;
use App\Realm;

class RealmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resultLink =
            json_decode(file_get_contents('https://eu.api.battle.net/wow/realm/status?locale=en_GB&apikey=geqhpfsqj3thw24vtfg6x43hkdp275je'), true);
        foreach ($resultLink['realms'] as $realm) {
			Realm::create([
			    'population' => $realm['population'],
                'type' => $realm['type'],
			    'queue' => $realm['queue'],
			    'status' => $realm['status'],
			    'name' => $realm['name'],
			    'slug' => $realm['slug'],
			    'battlegroup' => $realm['battlegroup'],
			    'locale' => $realm['locale'],
			    'timezone' => $realm['timezone'],
                'connected_realms' => serialize($realm["connected_realms"])
            ]);
        }
    }
}
