<?php

namespace Tests\Unit;

use App\Realm;
use Carbon\Carbon;
use PHPUnit\Framework\Assert;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RealmsTest extends TestCase
{
//    use DatabaseTransactions;

    public function testRealmsCanBeRetrieved() {
		$realmsFromDb = Realm::all('id', 'name', 'status', 'slug', 'queue', 'battlegroup');
    	$response = $this->get('/api/realms');
    	$response
			->assertStatus(200)
			->assertJson(['data' => ['realms' => $realmsFromDb->toArray()]]);
	}

	public function testSingleRealmCanBeRetrieved()
	{
		$realmFromDb = Realm::all()->random(1)->first();
		$response = $this->get('/api/realms/' . $realmFromDb->slug);
		$response
			->assertStatus(200)
			->assertJsonFragment($realmFromDb->toArray());
	}

	public function testCanUpdateIfTimeElapsed()
	{
		$realm = Realm::find(1);
		$timestamp = Carbon::now()->subMinutes(6);
		$realm->updated_at = $timestamp;
		$realm->save();
		$response = $this->patch('/api/realms');
		$response
			->assertStatus(200)
			->assertJsonFragment(['Realm status updated.']);
		$realmAfterUpdate = Realm::find(1);
		Assert::assertTrue($realmAfterUpdate->updated_at->diffInMinutes(Carbon::now()) <= 1);
	}

	public function testCantUpdateIfTimeHasntElapsed()
	{
		$realm = Realm::find(1);
		$realm->updated_at = Carbon::now();
		$realm->save();
		$response = $this->patch('/api/realms');
		$realmAfterUpdate = Realm::find(1);
		$timeElapsedSinceUpdated = Carbon::now()->diffInMinutes($realmAfterUpdate->updated_at);
		$response
			->assertStatus(400)
			->assertJsonFragment(["Please try again in " . (5 - $timeElapsedSinceUpdated) . " minutes."]);
	}
}

