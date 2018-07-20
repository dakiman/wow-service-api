<?php

namespace App\Providers;

use App\Realm;
use Illuminate\Support\ServiceProvider;

class RealmService extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
	}

	public static function updateRealmData() {
		$resultLink =
			json_decode(file_get_contents('https://eu.api.battle.net/wow/realm/status?locale=en_GB&apikey=geqhpfsqj3thw24vtfg6x43hkdp275je'), true);
		foreach($resultLink['realms'] as $realm) {
			$currentRealm = Realm::where('name', $realm['name'])->first();
			$currentRealm->population = $realm['population'];
			$currentRealm->queue = $realm['queue'];
			$currentRealm->status = $realm['status'];
			$currentRealm->save();
		}
	}

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
