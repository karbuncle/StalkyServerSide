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

Route::get('profile', ['middleware' => 'auth', function() {
	// TODO this should be removed at some point...
	// for testing login function only
	return response()->json( [ 'message' => 'logged in!' ], 200 );
}]);

Route::get( 'logout', function() {
	// somehow this doesn't work if put in a controller!
	Auth::logout();
	return response()->json( [ 'message' => trans('auth.logged_out') ], 200);
});
Route::post( 'login', 'Auth\AuthController@login');


// TODO all except login, logout should use ['middleware' => 'auth', 'uses' => 'XXXController']

Route::resource('users','UserController');
Route::resource('comments','CommentController');

/*
 * User route, have to specify one by one because not using default controller methods!
 */
Route::get( 'user/{id}', [
    'as' => 'id', 'uses' => 'UserController@search'
]);

/*
 * Rating routes, have to specify one by one because not using default controller methods!
 */
// Route::post( 'rate/{who}', [ /* 'middleware' => 'auth', */ 'uses' => 'RatingController@update' ] );
Route::delete( 'rate/{who}', [ /* 'middleware' => 'auth',*/ 'uses' => 'RatingController@clear' ] );
Route::get( 'rate', [ 'uses' => 'RatingController@show' ] );
Route::post( 'rate', [ 'uses' => 'RatingController@update' ] );
Route::get( 'users/top/{$limit}', [ 'uses' => 'UserController@top' ] );
