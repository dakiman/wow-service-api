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

    public function getSingle($slug, RealmService $realmService)
    {
        $realm = $realmService->getRelevantSingleRealmData($slug);
        return response(['realm' => $realm], 200);
    }

    public function requestUpdate(RealmService $realmService)
    {
        $realmService->updateRealmData();
        $allRealms = Realm::all('name', 'status', 'slug', 'queue', 'battlegroup');
        return response(['realms' => $allRealms], 200);
    }
}
