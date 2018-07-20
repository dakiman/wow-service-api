<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Realm;

class RealmController extends Controller
{
    public function get() {
		return Realm::all('name', 'status', 'queue', 'updated_at');
	}
}
