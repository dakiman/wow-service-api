<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        request()->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:30|min:2',
            'password' => 'required|string|min:8|max:32'
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password'))
        ]);

        return response()->json(['user' => $user], 201);

    }

    public function login()
    {
        $result = auth()->attempt(request(['email', 'password']));
        if (!$result) {
            return response()->json([
                'errors' => [
                    'auth' => [
                        'Wrong email or password.'
                    ]
                ],
            ], 422);
        }

        $user = User::whereEmail(request('email'))->first();

        // Send an internal API request to get an access token
        $data = [
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => env('OAUTH_PERSONAL_KEY'),
            'username' => request('email'),
            'password' => request('password'),
        ];

        $request = Request::create('/oauth/token', 'POST', $data);

        try {
            $response = app()->handle($request);
        } catch (\Exception $e) {
            return response()->json(['errors' => ['auth' => ['Problem with authentication client. Please inform developers']]], 500);
        }

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json(['message' => 'Request cannot be handled'], 422);
        }

        $data = json_decode($response->getContent());

        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
        ], 200);
    }

    public function logout()
    {
        try {
            $accessToken = auth()->user()->token();

            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $accessToken->id)
                ->update([
                    'revoked' => true
                ]);

            $accessToken->revoke();
        } catch (\Exception $e) {
            return response()->json(["errors" => ["auth" => ["Request could not be processed. Please reauthenticate and attempt again."]]], 404);
        }
        return response()->json(["message" => "Logged out!"], 200);
    }

    public function getUser()
    {
        return response()->json(auth()->user(), 200);
    }
}
