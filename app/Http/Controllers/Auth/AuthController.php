<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use GuzzleHttp\Client;

class AuthController extends Controller
{
	const FB_TOKEN_DEBUG_URI = 'https://graph.facebook.com/v2.5/debug_token';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    public function login( Request $request ) {
    	$client = new Client();
    	
    	$userId = $request->input('userId');
    	$userToken = $request->input('userToken');
    	$appToken = $request->input('appToken');
    	
    	$response = $client->request( 'GET', self::FB_TOKEN_DEBUG_URI, [ 
    		'input_token'  => $userToken,
    		'access_token' => $appToken
    	] ); // TODO: this thing seems to throw exception if the request gives 4xx errors, need confirm
    	if( isset( $response->data ) ) {
    		$data = $response->data;
    		if( $data->app_id == config( 'app.facebook_app_id' ) && $data->is_valid && $data->user_id == $userId ) {
    			// token is valid
    			Auth::login( User::getUserById( $userId ) );
    		} else {
    			// token is not valid
    			// some action should be taken??
    		}
    	}
    	
    }
    public function getUserById( $userId ) {
    	// TODO should return a User object
    }
    private function register( $userId ) {
    	// TODO: adds an entry to database of the given $userId
    	// should return a User object
    }
    
    public function logout() {
    	Auth::logout();
    	return response()->json([], 200);
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
