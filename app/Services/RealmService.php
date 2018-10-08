<?php

namespace App\Services;

use App\Exceptions\RealmCantUpdateException;
use App\Exceptions\RealmNameNotFound;
use App\Realm;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RealmService
{
    protected $blizzardService;

    public function __construct(BlizzardService $blizzardService)
    {
        $this->blizzardService = $blizzardService;
    }

    public function updateRealmData() {
        $timeSinceUpdated = $this->getTimeSinceUpdate();
        if($timeSinceUpdated > 5) {
            $response = $this->blizzardService->getRealmsData();
            $realmsData = json_decode($response->getBody()->getContents(), true);
            foreach($realmsData['realms'] as $realm) {
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
        } catch (ModelNotFoundException $e) {
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