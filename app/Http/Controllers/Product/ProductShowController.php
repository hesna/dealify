<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductShowController
{

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        return response()->json($product);
    }
}
