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
        request()->validate([
            'name' => 'required|string',
            'realm' => 'required|string'
        ]);
        $response = $this->blizzardService->getCharacter(
            request()->name,
            request()->realm
        );
        if ($response->getStatusCode() == 200) {
            $character = Character::make(json_decode($response->getBody()->getContents(), true));
            $character->user_id = auth()->user()->id;
            $character->save();
        }
        return response($character, 201);
    }

    public function get()
    {
        $characters = auth()->user()->characters;
        if (!$characters->isEmpty()) {
            return response()->json($characters, 200);
        } else {
            return response(['errors' => ['character' => ['No characters found.']]], 404);
        }
    }

    public function delete($id)
    {
        try {
            $character = Character::find($id);
            if (auth()->user()->id == $character->user_id) {
                $character->delete();
                return response(['success' => 'Character sucessfully deleted.'], 200);
            } else {
                return response(['errors' => ['character' => ['You do not own this character.']]], 403);
            }
        } catch (\Throwable $e) {
            return response(['errors' => ['character' => ['Character not found.']]], 404);
        }
    }

}
