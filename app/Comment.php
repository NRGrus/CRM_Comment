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


    protected $fillable =[
        'author_id', 'text', 'subject_type', 'subject_id', 'payload'
    ];


    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
