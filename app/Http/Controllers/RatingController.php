<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{
    /**
     * Stores a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rate( Request $request, $user_id_to )
    {
    	$param = [
        	'user_id_from' => Auth::user()->facebook_id,
        	'user_id_to'   => $user_id_to
        ];
        $transaction = Rating::create( $param );
        populateTransaction( $transaction, $request );
        $transaction->save();
        return response()->json( [ 'message' => trans( 'rating.created', $param ) ], 200 );
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update( Request $request, $user_id_to )
    {
    	$param =  [ 
        	'user_id_from' => Auth::user()->facebook_id, 
        	'user_id_to'   => $user_id_to,
        ];
        $transaction = Rating::where( $param );
        populateTransaction( $transaction, $request );
        $transaction->save();
        return response()->json( [ 'message' => trans( 'rating.updated', $param ) ], 200 );
    }
    
    private function populateTransaction( Rating $transaction, Request $request ) {
    	foreach( Rating::RATING_TYPES as $rating_type ) {
    		$column = Rating::RATING_COLUMN_PREFIX . $rating_type;
    		$rating = $request->input( $rating_type );
    		if( $rating >= 0 ) {
    			$transaction->$column =
    				$rating > Rating::MAX_RATING ? Rating::MAX_RATING : $rating;
    		}
    	}
    }

    /**
     * Removes the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function clear( $user_id_to )
    {
    	$param = [
    		'user_id_from' => Auth::user()->facebook_id,
    		'user_id_to'   => $user_id_to,
    	];
    	Rating::where( $param )->delete();
    	return response()->json( [ 'message' => trans( 'rating.deleted', $param ) ], 200 );
    }

}
