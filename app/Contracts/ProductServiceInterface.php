<?php

namespace App\Contracts;

use App\Models\Product;
use App\Models\User;

interface ProductServiceInterface
{
    /**
     * Method comment
     *
     * @param $userId $userId the user to comment
     * @param $product $product the product to comment on
     * @param $data $data the data containing the most importantly the COMMENT
     * @return void
     */
    public function comment(Product $product, User $user = null, $data);
}
