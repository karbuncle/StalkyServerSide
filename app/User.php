<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\FacebookUtil;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract
{
	use Authenticatable, Authorizable;
    protected $table = 'users';
    protected $fillable = ['facebook_id'];

    public function hasManyComments() {
        return $this->hasMany('App\Comment', 'user_id_to', 'facebook_id');
    }

    public function hasManyRatings() {
        return $this->hasMany('App\Rating', 'user_id_to', 'facebook_id');
    }

    public function getAverageRatings() {
    	return Rating::getAllAverageRatings( $this->facebook_id );
    }
    
    public function getComments() {
    	return $this->hasManyComments()->get();
    }
    public static function getUserById( $userId ) {
    	return self::firstOrCreate( [ 'facebook_id' => $userId ] );
    }
    public static function firstOrCreate( array $attributes ) {
		$user = parent::firstOrCreate( $attributes );
		// TODO should update the database with some info when creating
		if( !$user->name ) {
			try {
	    		$response = FacebookUtil::getInstance()->graphRequest( 'GET', $attributes[ 'facebook_id' ] );
	    		if( $response->getStatusCode() == 200 ) {
	    			$user->name = json_decode( $response->getBody()->getContents() )->name;
	    			$user->save();
	    		}
    		} catch( RequestException $e ) {
    			return response()->json( [ 'message' => trans('auth.facebook_request_failed') ], 500 );
    		}
		}
		return $user;
    }
    
}
