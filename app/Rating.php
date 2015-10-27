<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    
    protected $table = 'ratings';
    const RATING_COLUMN_PREFICES = 'rating_';
    
    /**
     * Retrieves the average rating of a user under a certain rating type.
     *
     * @param \App\User $askingForUser
     * @param \App\RatingType $ratingType
     * @return double
     */
    public static function getAverageRating( $askingFor, $ratingType ) {
    	$columnOfType = self::RATING_COLUMN_PREFICES . $ratingType;
    	self::where( 'user_id_to' , '=' , $askingFor )
    	        ->where( $columnOfType , '<>' , 0 )
    	        ->get()
    	      ->avg($columnOfType);
    }
    
}