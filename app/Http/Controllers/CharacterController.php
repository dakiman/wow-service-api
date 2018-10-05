<?php

namespace App\Http\Controllers;

use App\Character;
use App\Services\BlizzardService;

class CharacterController extends Controller
{
    private $blizzardService;

    public function __construct(BlizzardService $blizzardService)
    {
        $this->blizzardService = $blizzardService;
    }

    public function store()
    {
        $character = $this->blizzardService->getAndSaveCharacter(
            request()->name,
            request()->realm
        );
        return response()->api(['character' => $character], 201);
    }

    public function get()
    {
        $characters = $this->blizzardService->getAllCharactersForUser();
        return response()->api(['characters' => $characters], 200);
    }

    public function delete($id)
    {
        $this->blizzardService->deleteCharacterById($id);
        return response()->api(['message' => 'Character was sucessfully deleted.'], 200);
    }

}
