<?php

namespace App\Http\Controllers;

use App\Character;
use App\Services\CharacterService;

class CharacterController extends Controller
{
    private $characterService;

    public function __construct(CharacterService $characterService)
    {
        $this->characterService = $characterService;
    }

    public function store()
    {
        $this->validate(request(), [
            'name' => 'required|string',
            'realm' => 'required|string'
        ]);
        $character = $this->characterService->getAndSaveCharacter(
            request()->name,
            request()->realm
        );
        return response()->api(['character' => $character], 201);
    }

    public function get()
    {
        $characters = $this->characterService->getAllCharactersForUser();
        return response()->api(['characters' => $characters], 200);
    }

    public function delete($id)
    {
        $this->characterService->deleteCharacterById($id);
        return response()->api(['message' => 'Character was sucessfully deleted.'], 200);
    }

}
