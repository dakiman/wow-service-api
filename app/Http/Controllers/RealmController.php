<?php

namespace App\Http\Controllers;

use App\Services\RealmService;
use App\Realm;
use Carbon\Carbon;



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

	public function requestUpdate(RealmService $realmService)
	{
	    $updateTime = $realmService->updateRealmData();
	    if($updateTime === true) {
            $allRealms = Realm::all('name', 'status', 'slug', 'queue', 'battlegroup');
            return response(['success' => ['realms' => ['Realm status updated.']], 'realms' => $allRealms	], 200);
        }
        return response(['errors' => ['realms' => ['Realm status not updated. Please wait another ' . (5 - $updateTime) . ' minutes.']]], 400);
	}
}
