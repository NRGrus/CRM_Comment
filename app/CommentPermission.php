<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentPermission extends Model
{
    protected $fillable = [
        'comment_id', 'permission_type', 'user_id'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
