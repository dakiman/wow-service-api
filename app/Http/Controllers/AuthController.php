<?php

namespace App\Http\Controllers;

use App\Services\AuthenticationService;
use App\User;
use DB;

class AuthController extends Controller
{
	public function register()
	{
		request()->validate([
			'email' => 'required|email|unique:users',
			'name' => 'required|string|max:30|min:2',
			'password' => 'required|string|min:8|max:32'
		]);
        $user = User::create(request()->all(), [
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);
		return response()->api(['user' => $user], 201);
	}

	public function login(AuthenticationService $authenticationService)
	{
        $data = $authenticationService->authenticateUser();
		return response()->api($data, 200);
	}

	public function logout(AuthenticationService $authenticationService)
	{
        $authenticationService->logoutUser();
		return response()->json(["message" => "Logged out!"], 200);
	}

	public function getUser()
	{
		return response()->json(auth()->user(), 200);
	}
}
