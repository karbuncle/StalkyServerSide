<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*
        $comments = Comment::all();
        $json = array();
        foreach($comments as $comment){
            $user_from = $comment->userFrom()->first();
            $user_to = $comment->userTo()->first();

            $user_from_json = array('id'=>$user_from->facebook_id, 'name'=>$user_from->name);
            $user_to_json = array('id'=>$user_to->facebook_id, 'name'=>$user_to->name);

            $json[] = array(
            	'comment' => $comment->comment,
                'userFrom' => $user_from_json, 'userTo'=>$user_to_json,
                'created_at' => $comment->created_at, 'updated_at' => $comment->updated_at
            );
        }*/
        // Note: serialization will take care of it.

        return response()->json( Comment::all() );
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
        $comment = Comment::where('user_id_from', '=', $input['user_id_from'])
            ->where('user_id_to', '=', $input['user_id_to'])->first();

        if ($comment == null) {
            $comment = new Comment;

            $user_from = User::where('facebook_id', '=', $input['user_id_from'])->first();
            $user_to = User::where('facebook_id', '=', $input['user_id_to'])->first();

            if (!($comment->userTo()->associate($user_to))) {
                return response()->json(array('status' => 'error'));
            }
            if (!($comment->userFrom()->associate($user_from))) {
                return response()->json(array('status' => 'error'));
            }
        }

        $comment->comment = $input['comment'];
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
        /*$comment = Comment::find($id);
        $user_from = $comment->userFrom()->first();
        $user_to = $comment->userTo()->first();

        $user_from_json = array('id'=>$user_from->id, 'name'=>$user_from->name);
        $user_to_json = array('id'=>$user_to->id, 'name'=>$user_to->name);

        $json[] = array(
        	'comment' => $comment->comment,
            'userFrom' => $user_from_json, 'userTo'=>$user_to_json
        );*/

        return response()->json( Comment::find($id) );
    }

    public function showOne(Request $request)
    {
        //
        $result = Comment::where( [
            'user_id_from' => $request->input( 'user_id_from' ),
            'user_id_to' => $request->input( 'user_id_to' )
        ] )->first() or $result = array(
            'user_id_from' => $request->input( 'user_id_from' ),
            'user_id_to' => $request->input( 'user_id_to' ),
            'commenter' => '',
            'commented' => '',
            'comment' => ''
        );
        return response()->json( $result );
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
