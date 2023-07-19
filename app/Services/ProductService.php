<?php

namespace App\Services;

use Exception;

use App\Models\Product;
use App\Exceptions\QueryException;
use App\Contracts\FileBuilderInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CommentRepositoryInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        public CommentRepositoryInterface $commentRepositoryInterface,
        public FileBuilderInterface $fileBuilderInterface
    ) {
    }

    public function comment(Product $product, $user = null, $data)
    {
        // dd($user);
        try {
            $this->commentRepositoryInterface->store($product, $user, $data);
        } catch (Exception $e) {
            throw new QueryException('Something went wrong');
        }
    }
}
