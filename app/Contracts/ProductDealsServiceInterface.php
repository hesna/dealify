<?php
namespace App\Contracts;

use App\Models\Product;
use App\Classes\Basket;

/**
 * Interface ProductDealsServiceInterface
 * @package App\Contracts
 */
interface ProductDealsServiceInterface
{
    /**
     * @param Product $product
     * @param array
     * @return void
     */
    public function setProductDeals(Product $product, array $deals) : void;

    /**
     * @param Basket $basket
     * @return void
     */
    public function applyDeals(Basket $basket) : void;

    /**
     * @param Product $product
     * @return int number of deleted deals
     */
    public function deleteProductDeals(Product $product): int;

    /**
     * @param Product $product
     * @return mixed
     */
    public function getProductDeals(Product $product);
}
