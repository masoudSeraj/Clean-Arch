<?php

namespace App\Repositories;

use App\Contracts\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CommentRepository implements CommentRepositoryInterface
{
    public function store(Model $model, User $user = null, $comment)
    {
        Comment::create([
            'comment' => $comment,
            'user_id' => $user->id,
            'commentable_id' => $model->id,
            'commentable_type' => get_class($model),
        ]);
    }
}
