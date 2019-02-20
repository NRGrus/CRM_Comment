<?php

namespace App\Policies;

use App\Comment;
use App\User;
use Dogovor24\Authorization\Services\AuthAbilityService;
use Dogovor24\Authorization\Services\AuthUserService;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;
    protected $authService;

    public function __construct()
    {
        $this->authService = new AuthUserService();
    }

    public function view(?User $user, Comment $comment)
    {
        return (
                (new AuthAbilityService())->userHasAbility('comment-view') ||
                $comment->permissions->where('permission_type', 'view')->where('user_id', $this->authService->getId())->count() > 0 ||
                $comment->author_id == $this->authService->getId()
        );
    }

    public function create(?User $user)
    {
        return true;
    }

    public function update(?User $user, Comment $comment)
    {
        return (
            (new AuthAbilityService())->userHasAbility('comment-update') ||
            $comment->permissions->where('permission_type', 'update')->where('user_id', $this->authService->getId())->count() > 0 ||
            $comment->author_id == $this->authService->getId()
        );
    }

    public function delete(?User $user, Comment $comment)
    {
        return (
            (new AuthAbilityService())->userHasAbility('comment-delete') ||
            $comment->permissions->where('permission_type', 'delete')->where('user_id', $this->authService->getId())->count() > 0 ||
            $comment->author_id == $this->authService->getId()
        );
    }
}
