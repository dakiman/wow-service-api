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
            ->assertStatus(401);
    }

    public function testRestrictedRouteAuthenticated()
    {
        $user = factory(User::class)->create();
        $response = $this->actingAs($user, 'api')->get('/api/user');
        $response
            ->assertStatus(200);
    }
}
