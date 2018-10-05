<?php

namespace App\Http\Controllers;

use App\Services\RealmService;

class RealmController extends Controller
{
    protected $realmService;

    public function __construct(RealmService $realmService)
    {
        $this->realmService = $realmService;
    }

    public function get()
    {
        $realms = $this->realmService->getAllRealmsData();
        return response()->api(['realms' => $realms], 200);
    }

    public function getSingle($slug)
    {
        $realm = $this->realmService->getRelevantSingleRealmData($slug);
        return response()->api(['realm' => $realm], 200);
    }

    public function requestUpdate()
    {
        $this->realmService->updateRealmData();
        $realms = $this->realmService->getAllRealmsData();
        return response()->api(['info' => 'Realm status updated.', 'realms' => $realms], 200);
    }
}
