<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Realm;
use Carbon\Carbon;
use App\Providers\RealmService;


class RealmController extends Controller
{
	public function get()
	{
		$realms = Realm::all('id', 'name', 'status', 'slug', 'queue', 'battlegroup');
		return response(['realms' => $realms], 200);
	}

	public function getSingle($slug)
	{
		try {
			$realm = Realm::where('slug', $slug)->get();
			$date = Carbon::now();
			$realm[0]['currentTime'] = $date;
			return response(['realm' => $realm], 200);
		} catch (\Exception $e) {
			return response(['errors' => ['lookup' => [$e->getMessage()]]], 404);
		}
	}

	public function requestUpdate()
	{
		$realm = Realm::find(1);
		$currentTime = Carbon::now();
		$timeElapsedSinceUpdated = $currentTime->diffInMinutes($realm->updated_at);
		if ($timeElapsedSinceUpdated >= 5) {
			RealmService::updateRealmData();
			$allRealms = Realm::all('name', 'status', 'slug', 'queue', 'battlegroup');
			return response(['success' => ['realms' => ['Realm status updated.']], 'realms' => $allRealms	], 200);
		} else {
			return response(['errors' => ['realms' => ['Realm status not updated. Please wait another ' . (5 - $timeElapsedSinceUpdated) . ' minutes.']]], 400);
		}
	}
}
