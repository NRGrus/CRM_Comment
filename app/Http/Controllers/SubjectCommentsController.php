<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectCommentShowRequest;
use App\Http\Resources\CommentResource;
use App\Subject;
use Dogovor24\Authorization\Services\AuthUserService;

class SubjectCommentsController extends Controller
{
    protected $authService;

    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
//        $this->authorizeResource(Comment::class);
        $this->authService = new AuthUserService();
    }

    /**
     * @param SubjectCommentShowRequest $request
     * @return CommentResource
     */
    public function show(SubjectCommentShowRequest $request, $id)
    {
        $comments = Subject::query()
                            ->where('subject_type', $request->subject_type)
                            ->where('subject_id', $request->subject_id)->first()
                            ->comments()
                            ->where('author_id', $this->authService->getId())
                            ->orWhereHas('permissions', function ($query){
                                    $query->where('permission_type', 'view')
                                        ->where('user_id', $this->authService->getId());
                            });

        return CommentResource::collection($comments->paginate(10));
    }
}
