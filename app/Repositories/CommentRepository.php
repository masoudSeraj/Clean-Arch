<?php namespace App\Repositories;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use App\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{    
   public function store(Product $product, User $user=null, array $data)
   {
      Comment::create([
         'comment' => $data['comment'], 
         'user_id' => $user->id, 
         'commentable_id' => $product->id, 
         'commentable_type' => $product
     ]);
   }

}
