<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rating;
use App\Http\Controllers\Controller;

// TODO messages should be put in a language file in resources/lang/en/
class RatingController extends Controller
{
    /**
     * Stores a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function rate(Request $request)
    {
        $transaction = Rating::create( [
        	'user_id_from' => Auth::user()->facebook_id,
        	'user_id_to'   => $request->input( 'who' )
        ] );
        populateTransaction( $transaction, $request );
        $transaction->save();
        return response()->json( [ 'message' => 'User has been rated' ], 200 );
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $transaction = Rating::where( [ 
        	'from' => Auth::user()->facebook_id, 
        	'to'   => $request->input( 'who' ),
        ] );
        populateTransaction( $transaction, $request );
        $transaction->save();
        return response()->json( [ 'message' => 'A rating has been updated' ], 200 );
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
    public function clear(Request $request)
    {
    	Rating::where( [
    			'from' => Auth::user()->facebook_id,
    			'to'   => $request->input( 'who' ),
    	] )->delete();
    	return response()->json( [ 'message' => 'A rating has been deleted' ], 200 );
    }

}
