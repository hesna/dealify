<?php
namespace App\Services;

use App\Contracts\ProductServiceInterface;
use App\Models\Product;

class ProductService implements ProductServiceInterface
{
    /**
     * @param array $ids
     * @return array
     */
    public function getProductsByIds(array $ids) : array
    {
        $products = Product::select(['id', 'name', 'price'])->whereIn('id', $ids)->get();

        return $products->toArray();
    }
}
