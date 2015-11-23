<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use App\FacebookUtil;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use GuzzleHttp\Exception\RequestException;
use Validator;

class AuthController extends Controller
{
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
    	$userId = $request->input('userId');
    	$userToken = $request->input('userToken');
        
    	if( $userId && $userToken ) {
    		// both params not null
    		try {
	    		$response = FacebookUtil::getInstance()->getDebugToken( $userToken );
    		} catch( RequestException $e ) {
    			var_dump( $e->getResponse() );
    			return response()->json( [ 'message' => trans('auth.facebook_request_failed') ], 500 );
    		}
	    	if( $response->getStatusCode() == 200 ) {
	    		$data = json_decode( $response->getBody()->getContents() )->data;
	    		if( $data->app_id == config( 'app.facebook_app_id' ) && $data->is_valid && $data->user_id == $userId ) {
	    			// token is valid
	    			Auth::login( User::getUserById( $userId ) );
	    			return response()->json( [ 'message' => trans('auth.logged_in') ], 200 );
	    		} else {
	    			return response()->json( [ 'message' => trans('auth.failed') ], 401 );
	    		}
	    	}
	    	return response()->json( [], 200 );
    	}
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
