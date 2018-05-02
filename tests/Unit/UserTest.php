<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Character;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    public function testRestrictedRoute()
    {
        $response = $this->get('/api/user');
        $response->assertStatus(401);
    }

    public function testRestrictedRouteAuthenticated()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->get('/api/user');
        $response->assertStatus(200);
    }

    public function testUserShouldRegister()
    {
        $faker = \Faker\Factory::create();
        $user = $this->getUser();
        $response = $this->json('POST', '/api/register', $user);
        unset($user['password']);
        $response
            ->assertStatus(201)
            //assert that the response contains in itself the given json structure
            ->assertJson(['user' => $user]);
    }

    public function testLoginShouldReturnToken()
    {
        $password = str_random(10);
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);
        $response = $this->json('POST', '/api/login', ['email' => $user->email, 'password' => $password]);
        $response
            ->assertStatus(200)
            //assert that the response has the following json somewhere
            ->assertJsonFragment($user->toArray());
    }

    public function testShouldReturnAuthenticatedUser()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->get('/api/user');
        $response
            ->assertStatus(200)
            ->assertJsonFragment($user->toArray());
    }

    public function testRegistrationEmailMustBeValid()
    {
        $user = $this->getUser();
        $user['email'] = str_random(10);
        $response = $this->json('POST', '/api/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(["The email must be a valid email address."]);
    }

    public function testRegistrationEmailCantBeEmpty()
    {
        $user = $this->getUser();
        unset($user['email']);
        $response = $this->json('POST', '/api/register', $user);
        $response
            ->assertStatus(400)
            ->assertJsonValidationErrors('email')
            ->assertJsonFragment(['The email field is required.']);
    }

    public function testRegistrationPasswordRequired()
    {
        $this->validationRequired('password');
    }

    public function testRegistrationNameRequired()
    {
        $this->validationRequired('name');
    }

    public function testRegistrationEmailRequired()
    {
        $this->validationRequired('email');
    }

    // public function testNameMaxLength()
    // {
    // 	$user = $this->getUser();
    // 	$user['name'] = str_random(31);
    // 	$response = $this->json('POST', '/api/register', $user);
    //     $response
    //         ->assertStatus(400)
    //         ->assertJsonValidationErrors('name')
    //         ->assertJsonFragment(["The name may not be greater than 30 characters."]);
    // }

    public function testNameMaxLength()
    {
        $this->validationMaxLength('name', 30);
    }

    public function testNameMinLength()
    {
        $this->validationMinLength('name', 2);
    }

    public function testPasswordMinLength()
    {
        $this->validationMinLength('password', 8);
    }

    public function testUserCanAddCharacter()
    {
        $user = factory(User::class)->create();
        $character = [
            'name' => 'Sernaos',
            'realm' => 'The Maelstrom'
        ];
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(200)
            ->assertJsonFragment($character);
    }

    public function testUserCantAddInvalidCharacter()
    {
        $user = factory(User::class)->create();
        $character = [
            'name' => 'Sernaos123',
            'realm' => 'The Maelstrom'
        ];
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(404)
            ->assertJsonValidationErrors('character');
    }

    public function testCharacterNameRequired()
    {
        $user = factory(User::class)->create();
        $character = [
            'realm' => 'The Maelstrom'
        ];
        $response = $this->actingAs($user, 'api')->json('POST', '/api/character', $character);
        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');
    }

    public function testCharacterRealmRequired()
    {
        $user = factory(User::class)->create();
        $character = [
            'name' => 'Sernaos'
        ];
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
			->assertStatus(404)
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
