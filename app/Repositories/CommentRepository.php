<?php namespace App\Repositories;

use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use App\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{    
   public function store(Model $model, User $user=null, $comment)
   {
      Comment::create([
         'comment' => $comment, 
         'user_id' => $user->id, 
         'commentable_id' => $model->id, 
         'commentable_type' => get_class($model)
     ]);
   }
}
