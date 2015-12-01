<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Rating;
use App\Http\Controllers\Controller;

class RatingController extends Controller
{

    /**
     * Updates the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // without authorization
    public function update( Request $request )
    {
	$user_id_from = $request->input( 'user_id_from' );
	$user_id_to = $request->input( 'user_id_to' );
    	$param =  [ 
        	'user_id_from' => $user_id_from, 
        	'user_id_to'   => $user_id_to,
        ];
    	
    	if( $param[ 'user_id_from' ] == $param[ 'user_id_to' ] ) {
    		 return response()->json( [ 'message' => trans( 'rating.unratable' ) ], 403 );
    	}
        $transaction = Rating::where( $param )->first() or $transaction = new Rating;
        self::populateTransaction( $transaction, $request );
        
        if( 
            $transaction->rated()->associate( $user_id_to ) &&
            $transaction->rater()->associate( $user_id_from ) &&
            $transaction->save()
        ) {
        	return response()->json( [ 'message' => trans( 'rating.updated', $param ) ], 200 );
        } else {
        	return response()->json( [ 'message' => trans( 'rating.failed' ) ], 500 );
        }
    }
    /* public function update( Request $request, $user_id_to )
    {
    	$param =  [ 
        	'user_id_from' => Auth::user()->facebook_id, 
        	'user_id_to'   => $user_id_to,
        ];
    	
    	if( $param[ 'user_id_from' ] == $param[ 'user_id_to' ] ) {
    		 return response()->json( [ 'message' => trans( 'rating.unratable' ) ], 403 );
    	}
        $transaction = Rating::where( $param )->first() or $transaction = Rating::create();
        self::populateTransaction( $transaction, $request );
        
        
        if( 
        	$transaction->rated()->associate( $user_id_to ) &&
            $transaction->rater()->associate( Auth::user()->facebook_id ) &&
            $transaction->save()
        ) {
        	return response()->json( [ 'message' => trans( 'rating.updated', $param ) ], 200 );
        } else {
        	return response()->json( [ 'message' => trans( 'rating.failed' ) ], 500 );
        }
    }*/
    
    private function populateTransaction( Rating $transaction, Request $request ) {
    	foreach( Rating::$RATING_TYPES as $rating_type ) {
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
