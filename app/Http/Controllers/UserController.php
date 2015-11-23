<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        $user = User::find($id);

        return response()->json(array("friendliness"=>$user->friendliness, "skills"=>$user->skills,
            "teamwork"=>$user->teamwork,"funfactor"=>$user->funfactor, "facebookid"=>$user->facebookid,
            "comments"=>$user->comments, "name"=>$user->name, "age"=>$user->age));

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
