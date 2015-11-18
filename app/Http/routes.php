<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('user/{id}', 'UserController@index');
Route::get('profile', ['middleware' => 'auth', function() {
	// TODO this should be removed at some point...
	// for testing login function only
	return response()->json( [ 'message' => 'logged in!' ], 200 );
}]);

Route::resource( 'login', 'Auth\AuthController@login');
Route::resource( 'logout', 'Auth\AuthController@logout');

// TODO all except login should use ['middleware' => 'auth', 'uses' => 'XXXController']

Route::resource('users','UserController');
Route::resource('ratings','RatingController');
Route::resource('comments','CommentController');
