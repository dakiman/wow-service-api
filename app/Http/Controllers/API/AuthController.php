<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:50|unique:users',
            'name' => 'required|string|max:30|min:2',
            'password' => 'required|string|min:8'
        ]);

        if($validator->fails()){
            return response()->json(['errors'=>$validator->getMessageBag(),'status' => 400]);
        } else {
            User::create([
                'name' => request('name'),
                'email' => request('email'),
                'password' => bcrypt(request('password'))
            ]);
            return response()->json(['status' => 201]);
        }
    }

    public function login()
    {
        $result = auth()->attempt(request(['email', 'password']));
        if(!$result)
        {
            return response()->json([
                'errors' => [
                    'auth'=> [
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

        /*
            -------------------------------
            CHECK CODE BLOCK FOR USEFULLNESS
        */

        // $accessToken = $user->token();

        // if($accessToken != null) {
        //     $refreshToken = DB::table('oauth_refresh_tokens')
        //     ->where('access_token_id', $accessToken->id)
        //     ->delete();
        //     $accessToken->delete();
        // }

        /*
            -------------------------------
        */

        $request = Request::create('/oauth/token', 'POST', $data);

        $response = app()->handle($request);

        // Check if the request was successful
        if ($response->getStatusCode() != 200) {
            return response()->json([
                'message' => 'Request cannot be handled',
                'status' => $response->getStatusCode()    
            ], 422);
        }

        $data = json_decode($response->getContent());

        return response()->json([
            'token' => $data->access_token,
            'user' => $user,
        ], 200);
    }

    public function logout()
    {
        $accessToken = auth()->user()->token();

        $refreshToken = DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update([
                'revoked' => true
            ]);

        $accessToken->revoke();

        return response()->json(['status' => 200]);
    }
}
