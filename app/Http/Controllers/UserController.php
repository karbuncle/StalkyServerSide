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
     * @return \Illuminate\Http\Response
     */
    public function search()
    {
        //
        $users = User::all();
        $json = array();
        foreach ($users as $user) {
            array_push($json, array("name"=>$user->name, "ratings"=>$user->hasManyRatings()));

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

        return response()->json(array("friendliness"=>Rating::getAverageRating('facebook_id', 'friendliness'),
            "skills"=>Rating::getAverageRating('facebook_id', 'skill'),
            "teamwork"=>Rating::getAverageRating('facebook_id','teamwork'),
            "funfactor"=>Rating::getAverageRating('facebook_id','funfactor'),
            "facebook_id"=>$user->facebook_id,
            "comments"=>$user->hasManyComments()->first(), "name"=>$user->name));

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
