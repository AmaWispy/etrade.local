<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CommentsType extends Model
{
    use HasFactory;
    protected $table = 'comment_types';
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
