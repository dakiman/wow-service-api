<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Realm;

class RealmController extends Controller
{
	public function get()
	{
		$realms = Realm::all('name', 'status', 'slug', 'queue', 'battlegroup');
		return response(['realms' => $realms], 200);
	}

	public function getSingle($slug)
	{
		try {
			$realm = Realm::where('slug', $slug)->get();
		} catch (\Exception $e) {
			return response(['errors' => ['lookup' => ['Realm not found.']]], 404);
		}
		return response(['realm' => $realm], 200);
	}
}
