<?php

namespace App\Services;

use App\Exceptions\RealmCantUpdateException;
use App\Exceptions\RealmNameNotFound;
use App\Realm;
use Carbon\Carbon;

class RealmService
{
    public function updateRealmData() {
        $timeSinceUpdated = $this->getTimeSinceUpdate();
        if($timeSinceUpdated > 5) {
            $resultLink =
                json_decode(file_get_contents('https://eu.api.battle.net/wow/realm/status?locale=en_GB&apikey=geqhpfsqj3thw24vtfg6x43hkdp275je'), true);
            foreach($resultLink['realms'] as $realm) {
                $currentRealm = Realm::where('name', $realm['name'])->first();
                $currentRealm->population = $realm['population'];
                $currentRealm->queue = $realm['queue'];
                $currentRealm->status = $realm['status'];
                $currentRealm->save();
            }
            return true;
        } else {
            throw new RealmCantUpdateException("Please try again in " . (5 - $timeSinceUpdated) . " minutes.");
        }
    }

    public function getRelevantSingleRealmData($slug) {
        try {
            $realm = Realm::where('slug', $slug)->get();
            $date = Carbon::now();
            $realm[0]['currentTime'] = $date;
            return $realm;
        } catch (\Exception $e) {
            throw new RealmNameNotFound("Realm not found.");
        }
    }

    public function getAllRealmsData() {
        return Realm::all('id', 'name', 'status', 'slug', 'queue', 'battlegroup');
    }

    private function getTimeSinceUpdate() {
        $realm = Realm::find(1);
        $currentTime = Carbon::now();
        $timeElapsedSinceUpdated = $currentTime->diffInMinutes($realm->updated_at);
        return $timeElapsedSinceUpdated;
    }


}