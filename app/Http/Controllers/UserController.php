<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Rating;

class UserController extends Controller
{
    /**
     * Given: a list of ids that were searched
     * Want: return all users with matching characters
     *
     * @param String $id
     * @return \Illuminate\Http\Response
     */
    public function search($id)
    {
        //
        $users = User::where('name', 'like', "%{$id}%")->get();
        $json = array();
        foreach ($users as $user) {
            $json[] = array(
                'name' => $user->name,
                'ratings' => $user->getAverageRatings(),
                'facebook_id' => $user->facebook_id
            );

        }

        return response()->json( $json );
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $user = User::where('facebook_id',$id)->first();

        return response()->json( array(
        	'facebook_id' => $user->facebook_id,
        	'ratings' => $user->getAverageRatings(),
            'comments' => $user->getComments(), 
        	"name"=> $user->name)
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

}
