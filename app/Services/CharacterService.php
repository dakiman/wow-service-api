<?php
/**
 * Created by PhpStorm.
 * User: dvanchov
 * Date: 10/5/2018
 * Time: 3:33 PM
 */

namespace App\Services;

use App\Character;
use App\Exceptions\CharacterNotFoundException;
use App\Exceptions\CharacterNotOwnedException;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CharacterService
{
    protected $blizzardService;
/*TODO MAKE THIS CALLS TO BLIZZ API ONLY*/
    public function __construct(BlizzardService $blizzardService)
    {
        $this->blizzardService = $blizzardService;
    }

    public function getAndSaveCharacter($name, $realm)
    {
        try {
            $response = $this->blizzardService->getCharacter($name, $realm);
            $character = Character::make(json_decode($response->getBody(), true));
            $character->user_id = auth()->user()->id;
            $character->save();
            return $character;
        } catch (ClientException $e) {
            throw new CharacterNotFoundException();
        }
    }

    public function getAllCharactersForUser()
    {
        $characters = auth()->user()->characters;
        if ($characters->isEmpty()) {
            throw new CharacterNotFoundException("You have no saved characters");
        }
        return $characters;
    }

    public function deleteCharacterById($id)
    {
        try {
            $character = Character::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw new CharacterNotFoundException();
        }
        if (auth()->user()->id == $character->user_id) {
            $character->delete();
        } else {
            throw new CharacterNotOwnedException("You cannot delete a character you do not own.");
        }

    }

}