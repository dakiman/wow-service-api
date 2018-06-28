<?php

namespace App\Http\Controllers;

use App\Character;

class CharacterController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://eu.api.battle.net/wow/',
            'query' => [
                'locale' => 'en_GB',
                'apikey' => env('WOW_API_KEY')
            ]
        ]);
    }

    public function store()
    {
        request()->validate([
            'name' => 'required|string',
            'realm' => 'required|string'
        ]);

        try {
            $response = $this->client->get('character/' . request()->realm . '/' . request()->name);
            if ($response->getStatusCode() == 200) {
                $character = Character::make(json_decode($response->getBody()->getContents(), true));
                $character->user_id = auth()->user()->id;
                $character->save();
            }
            return response($character, 201);
        } catch (\Exception $e) {
            return response(['errors' => ['character' => ['Error processing character.']]], 422);
        }
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
