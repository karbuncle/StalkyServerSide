<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    
    protected $table = 'ratings';
    const RATING_COLUMN_PREFIX = 'rating_';
    //const RATING_TYPES = [ 'friendliness', 'skill', 'teamwork', 'funfactor' ];
    const MAX_RATING = 5;
    
    /**
     * Retrieves the average rating of a user under a certain rating type.
     *
     * @param \App\User $askingForUser
     * @param \App\RatingType $ratingType
     * @return double
     */
    public static function getAverageRating( $askingFor, $ratingType ) {
    	$columnOfType = self::RATING_COLUMN_PREFIX . $ratingType;
    	return 1.8;
            self::where( 'user_id_to' , '=' , $askingFor )
    	        ->where( $columnOfType , '<>' , 0 )
    	        ->get()
    	      ->avg($columnOfType);
    }
    public static function getAllAverageRatings( $askingFor ) {
        $result = array();
        foreach( [ 'friendliness', 'skill', 'teamwork', 'funfactor' ] as $type ) {
            $result[ $type ] = getAverageRating( $askingFor, $type );
        }
        return $result;
    }
    
}