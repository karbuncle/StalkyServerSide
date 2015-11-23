<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //
    protected $table="comments";

    public function userTo(){
        return $this->belongsTo('App\User', 'user_id_to', 'facebook_id');
    }

    public function userFrom(){
        return $this->belongsTo('App\User', 'user_id_from', 'facebook_id');
    }
}
