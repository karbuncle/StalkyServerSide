<?php

namespace App;

//use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
//use Illuminate\Auth\Passwords\CanResetPassword;
//use Illuminate\Foundation\Auth\Access\Authorizable;
//use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
//use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
//use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model
{
    protected $table="users";

    public function hasManyComments( $foreign_key ) {
        return $this->hasMany('App\Comment', $foreign_key, 'id');
    }

    public function hasManyRatings() {
        return $this->hasMany('App\Rating', 'user_id_to', 'id');
    }

    public static function getUserById( $userId ) {
    	// TODO should update the database with some info when creating
    	return self::firstOrCreate( [ 'facebook_id' => $userId ] );
    }
    
}
