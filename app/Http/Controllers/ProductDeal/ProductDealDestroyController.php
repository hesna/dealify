<?php

namespace App\Http\Controllers\ProductDeal;

use App\Models\Deal;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductDealDestroyController
 * @package App\Http\Controllers
 */
class ProductDealDestroyController
{
    /**
     * destroys all deals of a product
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        Deal::where('product_id', $product->id)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
