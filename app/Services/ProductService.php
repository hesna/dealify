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
