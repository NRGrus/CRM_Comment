<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];


    protected $fillable = [
        'author_id', 'text', 'subject_type', 'subject_id'
    ];

    protected $hidden = [
        'deleted_at', 'created_at', 'updated_at'
    ];


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function permissions()
    {
        return $this->hasMany(CommentPermission::class, 'comment_id');
    }
}
