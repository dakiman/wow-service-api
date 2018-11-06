<?php

namespace App\Services;

use App\Exceptions\AuthenticationException;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthenticationService
{

    /**
     * @return array
     * @throws AuthenticationException
     */
    public function authenticateUser()
    {
        if (!auth()->attempt(request(['email', 'password']))) {
            throw new AuthenticationException("Wrong email or password.");
        }

        $user = User::whereEmail(request('email'))->first();
        $token = $this->requestTokenFromAuthServer();

        return ['token' => $token, 'user' => $user];
    }

    public function logoutUser()
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
            throw new AuthenticationException("Problem during logout, request couldnt be properly handled.", 500);
        }
    }

    /**
     * @return mixed
     * @throws AuthenticationException
     */
    private function requestTokenFromAuthServer()
    {
        $response = $this->handleRequestToAuthServer();
        $responseBody = json_decode($response->getContent());
        $token = $responseBody->access_token;
        return $token;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AuthenticationException
     */
    private function handleRequestToAuthServer()
    {
        $request = $this->createRequest();

        try {
            $response = app()->handle($request);
        } catch (\Exception $e) {
            throw new AuthenticationException("Problem with authentication server.");
        }

        if ($response->getStatusCode() != 200) {
            throw new AuthenticationException("Request was not properly handled.");
        }

        return $response;
    }

    /**
     * @return Request
     */
    private function createRequest()
    {
        $requestData = [
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => env('OAUTH_PERSONAL_KEY'),
            'username' => request('email'),
            'password' => request('password'),
        ];

        return Request::create('/oauth/token', 'POST', $requestData);
    }

}