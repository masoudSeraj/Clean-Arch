<?php

namespace App\Services;

use Exception;

use App\Models\Product;
use App\Exceptions\QueryException;
use App\Contracts\FileBuilderInterface;
use Illuminate\Support\Facades\Validator;
use App\Contracts\ProductServiceInterface;
use App\Contracts\CommentRepositoryInterface;
use App\Http\Resources\ProductResource;
use App\Models\Comment;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        public CommentRepositoryInterface $commentRepositoryInterface,
        public FileBuilderInterface $fileBuilderInterface
    ) {
    }

    public function comment(Product $product, $user = null, $data)
    {
        try {
            $this->commentRepositoryInterface->store($product, $user, $data['comment']);
        } catch (Exception $e) {
            throw new QueryException('Something went wrong');
        }
    }
    
    /**
     * Method store
     *
     * @param array $name [explicite description]
     *
     * @return void
     */
    public function storeCommand(array $name)
    {
        $validator = Validator::make($name, [
            'name'  => 'required|unique:products,name',
        ], [
            'name.required'  => 'Product name is required',
            'name.unique'   => 'Product name must be unique'
        ]);
        
        $validator->validate();
        collect($name['name'])->each(fn($product) => Product::create(['name' => $product]));
    }

    public function list()
    {
        return ProductResource::collection(Product::with('comments', 'comments.user')->get());
    }
}
