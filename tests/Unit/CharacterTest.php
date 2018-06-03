<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Character;

class CharacterTest extends TestCase
{
    use DatabaseTransactions;

    private function getCharacter($name = 'Sernaos', $realm = 'The Maelstrom')
    {
        return [
            'name' => $name,
            'realm' => $realm
        ];
    }

    public function testUserCanAddCharacter()
    {
        $user = factory(User::class)->create();
        $character = $this->getCharacter();
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(200)
            ->assertJsonFragment($character);
    }

    public function testUserCantAddInvalidCharacter()
    {
        $user = factory(User::class)->create();
        $character = $this->getCharacter(str_random(10) . '123');
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('character');
    }

    public function testCharacterNameRequired()
    {
        $user = factory(User::class)->create();
        $character = $this->getCharacter('');
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function testCharacterRealmRequired()
    {
        $user = factory(User::class)->create();
        $character = $this->getCharacter('Sernaos', '');
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('realm');
    }

    public function testUserCanGetCharacters()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user, 'api')->json('GET', '/api/character');
        $response
            ->assertStatus(200)
            ->assertJsonFragment($character->toArray());
    }

    public function testUserCanDeleteCharacters()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user, 'api')->json('DELETE', '/api/character/' . $character->id);
        $response
            ->assertStatus(200);
    }

    public function testUserCanDeleteOnlyOwnedCharacters()
    {
        $owner = factory(User::class)->create();
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create([
            'user_id' => $owner->id
        ]);
        $response = $this->actingAs($user, 'api')->json('DELETE', '/api/character/' . $character->id);
        $response
            ->assertStatus(403)
            ->assertJsonValidationErrors('character');
    }

    public function testUserCantDeleteNonExistentCharacters()
    {
        $user = factory(User::class)->create();
        $character = factory(Character::class)->create([
            'user_id' => $user->id
        ]);
        $character->delete();
        $response = $this->actingAs($user, 'api')->json('DELETE', '/api/character/' . $character->id);
        $response
            ->assertStatus(404)
            ->assertJsonValidationErrors('character');
    }
}
