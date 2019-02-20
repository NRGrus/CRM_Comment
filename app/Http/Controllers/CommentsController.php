<?php

namespace App\Http\Controllers;

use App\Comment;
use App\CommentPermission;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\IndexCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Subject;
use Dogovor24\Authorization\Services\AuthUserService;

class CommentsController extends Controller
{
    protected $authService;

    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->authorizeResource(Comment::class);
        $this->authService = new AuthUserService();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CreateCommentRequest  $request
     * @return \App\Http\Resources\CommentResource
     */
    public function store(CreateCommentRequest $request)
    {
        $subject = Subject::firstOrCreate([
            'subject_id'    => $request->subject_id,
            'subject_type'  => $request->subject_type
        ]);

        $comment = Comment::create([
            'author_id'     => $this->authService->getId(),
            'text'          => $request->text,
            'subject_id'    => $subject->id,
        ]);

        if ($request->payload != null) {

            foreach ($request->payload as $key => $value)
                foreach ($value as $id)
                    CommentPermission::create([
                        'comment_id'        => $comment->id,
                        'permission_type'   => $key,
                        'user_id'           => $id
                    ]);

        }

        return new CommentResource($comment);
    }


    /**
     * @param IndexCommentRequest $request
     * @param Comment $comment
     * @return CommentResource
     */
    public function show(IndexCommentRequest $request, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  Comment $comment
     *
     * @return \App\Http\Resources\CommentResource
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->update($request->only(['text', 'payload']));

        return new CommentResource($comment);
    }


    /**
     * @param IndexCommentRequest $request
     * @param Comment $comment
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(IndexCommentRequest $request, Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }
}
