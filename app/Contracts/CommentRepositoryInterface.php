<?php namespace App\Contracts;

use App\Models\User;
use App\Models\Product;

interface CommentRepositoryInterface
{        
    /**
     * Method comment
     *
     * @param $user $user the user to comment
     * @param $product $product the product to comment on
     * @param $data $data the data containing the most importantly the COMMENT
     *
     * @return void
     */
    public function store(Product $product, User $user=null, array $data);
}