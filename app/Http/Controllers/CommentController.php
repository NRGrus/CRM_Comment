<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CreateComment;
use App\Http\Requests\UpdateComment;
use App\Http\Resources\CommentResource;
use App\Subject;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return CommentResource::collection(Comment::with('subject')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateComment  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateComment $request)
    {
        $subject = Subject::create([
            'subject_id'    => $request->subject_id,
            'subject_type'  => $request->subject_type
        ]);

        $comment = Comment::create([
            'author_id'     => auth()->user()->id,
            'text'          => $request->text,
            'subject_id'    => $subject->id,
            'payload'       => $request->payload,
        ]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        $arr = json_decode($comment->payload);

        if (($arr != null && array_key_exists('show', $arr) && in_array(auth()->user()->id, $arr->show))
            || auth()->user()->id === $comment->author_id) {

            return new CommentResource($comment);
        }

        return response()->json(['error' => 'You do not have ability to show this comment'], 403);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateComment  $request
     * @param  Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateComment $request, Comment $comment)
    {
        $arr = json_decode($comment->payload);

        if (($arr != null && array_key_exists('update', $arr) && in_array(auth()->user()->id, $arr->update))
            || auth()->user()->id === $comment->author_id) {

            $comment->update($request->only(['text', 'payload']));

            return new CommentResource($comment);
        }
        return response()->json(['error' => 'You do not have ability to edit this comment'], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Comment $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $arr = json_decode($comment->payload);

        if (($arr != null && array_key_exists('delete', $arr) && in_array(auth()->user()->id, $arr->delete))
                        || auth()->user()->id === $comment->author_id) {

            $comment->delete();
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'You do not have ability to delete this comment'], 403);
    }
}
