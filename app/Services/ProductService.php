<?php
namespace App\Services;

use App\Contracts\ProductServiceInterface;
use App\Models\Product;

class ProductService implements ProductServiceInterface
{
    /**
     * @param array $ids
     * @param array $fields
     * @return array
     */
    public function getProductsArrayByIds(array $ids, array $fields) : array
    {
        return Product::select($fields)->whereIn('id', $ids)->get()->toArray();
    }

    /**
     * @param array $fields
     * @return Product
     */
    public function createProduct(array $fields): Product
    {
        return Product::create($fields);
    }

    /**
     * @param Product $product
     * @param array $fields
     * @return bool
     */
    public function updateProduct(Product $product, array $fields): bool
    {
        return $product->update($fields);
    }
}
