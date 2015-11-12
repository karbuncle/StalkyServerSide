<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $comments = Comment::all();
        $json = array();
        foreach($comments as $comment){
            $user_from = $comment->userFrom()->first();
            $user_to = $comment->userTo()->first();

            $user_from_json = array('id'=>$user_from->id, 'name'=>$user_from->name,
                'gender'=>$user_from->gender, 'age'=>$user_from->age);
            $user_to_json = array('id'=>$user_to->id, 'name'=>$user_to->name,
                'gender'=>$user_to->gender, 'age'=>$user_to->age);

            array_push($json, array("comment"=>$comment->comment,
                'userFrom'=>$user_from_json, 'userTo'=>$user_to_json));
        }

        return response()->json($json);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $comment = new Comment;
        $user_from = User::where('id','=',$input['user_id_from'])->first();
        $user_to = User::where('id', '=', $input['user_id_to'])->first();
//        $comment->user_id_from=$input['user_id_from'];
//        $comment->user_id_to= $input['user_id_to'];
        $comment->comment = $input['comment'];
//        if(!($comment->userFrom()->hasManyComments('user_id_from'))
//            && !($comment->userTo()->hasManyComments('user_id_to'))){
//            return response()->json(array('status'=>'error'));
//        }
        if(!($comment->userTo()->associate($user_to)) ){
            return response()->json(array('status'=>'error'));
        }
        if(!($comment->userFrom()->associate($user_from)) ){
            return response()->json(array('status'=>'error'));
        }
        if($comment->save()){
            return response()->json(array('status'=>'ok'));
        }

        return response()->json(array('status'=>'error'));
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
        $comment = Comment::find($id);
        $user_from = $comment->userFrom()->first();
        $user_to = $comment->userTo()->first();

        $user_from_json = array('id'=>$user_from->id, 'name'=>$user_from->name,
            'gender'=>$user_from->gender, 'age'=>$user_from->age);
        $user_to_json = array('id'=>$user_to->id, 'name'=>$user_to->name,
            'gender'=>$user_to->gender, 'age'=>$user_to->age);

        array_push($json,array("comment"=>$comment->comment,
            'userFrom'=>$user_from_json, 'userTo'=>$user_to_json));

        return response()->json($json);
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
        $input = $request->all();
        $comment = Comment::find($id);

        $user_from = User::where('id','=',$input['user_id_from'])->first();
        $user_to = User::where('id', '=', $input['user_id_to'])->first();

        $comment->comment = $input['comment'];

        if(!($comment->userTo()->associate($user_to)) ){
            return response()->json(array('status'=>'error'));
        }
        if(!($comment->userFrom()->associate($user_from)) ){
            return response()->json(array('status'=>'error'));
        }
        if($comment->save()){
            return response()->json(array('status'=>'ok'));
        }

        return response()->json(array('status'=>'error'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        if(Comment::destroy($id)){
            return response()->json(array('status'=>'ok'));
        }

        return response()->json(array('status'=>'error'));
    }
}
