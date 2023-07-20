<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Contracts\ProductServiceInterface;

class ProductController extends Controller
{
    public function __construct(public ProductServiceInterface $productServiceInterface)
    {

    }
    public function comment(Product $product, User $user=null, ProductRequest $request)
    {
        $validated = $request->validated();
        if(!isset($user->id)){
            $user = auth()->user();
        }
        
        $this->productServiceInterface->comment($product, $user, $validated);

    }
}
