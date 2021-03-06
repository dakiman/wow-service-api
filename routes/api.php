<?php

use Illuminate\Http\Request;

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register', 'API\AuthController@register');
Route::post('/login', 'API\AuthController@login');
Route::get('/realms', 'RealmController@get');
Route::get('/realms/{slug}', 'RealmController@getSingle');
Route::patch('/realms', 'RealmController@requestUpdate');

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', 'API\AuthController@logout');
    Route::get('/user', 'API\AuthController@getUser');
    Route::post('/character', 'CharacterController@store');
    Route::get('/character', 'CharacterController@get');
    Route::delete('/character/{id}', 'CharacterController@delete');
});
