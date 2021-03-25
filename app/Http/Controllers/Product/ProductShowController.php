<?php

namespace App\Http\Controllers\Product;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductShowController
{

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function __invoke(Product $product): ProductResource
    {
        return ProductResource::make($product);
    }
}
