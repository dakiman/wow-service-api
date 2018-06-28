<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class MetaTest extends TestCase
{
    use DatabaseTransactions;

    public function testRestrictedRoute()
    {
        $response = $this->get('/api/user');
        $response
            ->assertStatus(401)
            ->assertJsonFragment(['error' => ['Unauthenticated.']]);
    }

    public function testRestrictedRouteAuthenticated()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->get('/api/user');
        $response
            ->assertStatus(200);
    }

    public function testOAuthTokens()
    {
        $password = str_random(10);
        $user = factory(User::class)->create([
            'password' => bcrypt($password)
        ]);
        $response = $this->json('POST', '/api/login', ['email' => $user->email, 'password' => $password]);
        $response
            ->assertStatus(200)
            ->assertJsonFragment(['token']);

        $data = $response->decodeResponseJson();
        $secondResponse = $this
            ->withHeaders(['Authorization' => 'Bearer ' . $data['token']])
            ->json('GET', '/api/user');
        $secondResponse
            ->assertStatus(200);
    }
}
