<?php
namespace App\Contracts;

use App\Models\Product;

/**
 * Interface ProductServiceInterface
 * @package App\Contracts
 */
interface ProductServiceInterface
{
    /**
     * @param array $ids
     * @param array $fields
     * @return array of product arrays
     */
    public function getProductsArrayByIds(array $ids, array $fields): array;

    /**
     * @param Product $product
     * @param array $fields
     * @return bool
     */
    public function updateProduct(Product $product, array $fields): bool;

    /**
     * @param array $fields
     * @return Product
     */
    public function createProduct(array $fields): Product;
}
