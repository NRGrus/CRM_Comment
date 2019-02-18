<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = [
        'subject_type', 'subject_id'
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
