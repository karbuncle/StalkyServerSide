<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    
    protected $table = 'ratings';
    protected $hidden = [ 'id', 'updated_at', 'user_id_from', 'user_id_to', 'created_at' ];
    const RATING_COLUMN_PREFIX = 'rating_';
    public static $RATING_TYPES = [ 'friendliness', 'skill', 'teamwork', 'funfactor' ];
    const MAX_RATING = 5;
    
    
    public function rated() {
    	return $this->belongsTo( 'App\User', 'user_id_to', 'facebook_id' );
    }
    
    public function rater() {
    	return $this->belongsTo( 'App\User', 'user_id_from', 'facebook_id' );
    }
    /**
     * Retrieves the average rating of a user under a certain rating type.
     *
     * @param \App\User $askingForUser
     * @param \App\RatingType $ratingType
     * @return double
     */
    public static function getAverageRating( $askingFor, $ratingType ) {
    	$columnOfType = self::RATING_COLUMN_PREFIX . $ratingType;
    	return User::where( 'facebook_id', '=', $askingFor )->first()
    	         ->hasManyRatings()
    			 ->avg( $columnOfType );
    }
    public static function getAllAverageRatings( $askingFor ) {
        $result = array();
        foreach( self::$RATING_TYPES as $type ) {
            $result[ $type ] = floatval( self::getAverageRating( $askingFor, $type ) );
        }
        return $result;
    }
    
}