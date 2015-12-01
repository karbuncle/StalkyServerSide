<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table = 'comments';
    // don't serialize these columns when converting to json
    protected $hidden = [ 'id', 'created_at' ];
    protected $appends = [ 'commenter', 'commented' ];
    
    public function getCommenterAttribute() {
    	return $this->userFrom()->first()->name;
    }
    
    public function getCommentedAttribute() {
    	return $this->userTo()->first()->name;
    }

    public function userTo(){
        return $this->belongsTo('App\User', 'user_id_to', 'facebook_id');
    }

    public function userFrom(){
        return $this->belongsTo('App\User', 'user_id_from', 'facebook_id');
    }
    
}
