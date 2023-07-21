<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\ProductServiceInterface;
use App\Exceptions\QueryException;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function __construct(public ProductServiceInterface $productServiceInterface)
    {

    }

    public function comment(Product $product, User $user = null, ProductRequest $request)
    {
        if (! isset($user->id)) {
            $user = auth()->user();
        }

        try {
            $this->productServiceInterface->comment($product, $user, $request);
        } catch (QueryException $e) {
            return response()->json(['fail' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['success' => 'Comment successfully added'], Response::HTTP_OK);
    }

    public function list()
    {
        return $this->productServiceInterface->list();

    }
}
