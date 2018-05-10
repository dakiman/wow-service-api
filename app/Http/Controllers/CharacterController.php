<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Character;

class CharacterController extends Controller
{
    public function store()
    {
        request()->validate([
            'name' => 'required',
            'realm' => 'required'
        ]);
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://eu.api.battle.net/wow/character/'. request()->realm .'/' . request()->name . '?locale=en_GB&apikey=' . env('WOW_API_KEY'));
            if ($response->getStatusCode() == 200) {
                $character = Character::make(request()->all());
                $character->user_id = auth()->user()->id;
                $character->save();
            }
            return response(json_decode($response->getBody(), true), $response->getStatusCode());
        } catch (\Exception $e) {
            $response = $e->getResponse();
            return response(['errors' => ['character' => ['Character or realm is incorrect.']]], $response->getStatusCode());
        }
    }

    public function get()
    {
        $characters = auth()->user()->characters;
        if (!$characters->isEmpty()) {
            return response($characters, 200);
        } else {
            return response(['errors' => ['character' => ['No characters found.']]], 401);
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
