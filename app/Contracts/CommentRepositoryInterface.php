<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface CommentRepositoryInterface
{
    /**
     * Method comment
     *
     * @param $user $user the user to comment
     * @param $model $model the model to comment on
     * @param $data $data the data containing the most importantly the COMMENT
     * @return void
     */
    public function store(Model $model, User $user = null, $comment);
}
