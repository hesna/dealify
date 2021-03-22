<?php
namespace App\Http\Controllers\ProductDeal;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductDealIndexController
{

    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        return response()->json($product->deals);
    }
}
