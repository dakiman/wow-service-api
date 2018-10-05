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
use \GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class BlizzardService
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getAndSaveCharacter($name, $realm)
    {
        try {
            $response = $this->client->get('character/' . $realm . '/' . $name);
            $character = Character::make(json_decode($response->getBody()->getContents(), true));
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
            $character = Character::find($id);
            if (auth()->user()->id == $character->user_id) {
                $character->delete();
            } else {
                throw new CharacterNotOwnedException("You cannot delete a character you do not own.");
            }
        } catch (\Exception $e) {
            throw new CharacterNotFoundException();
        }
    }

}