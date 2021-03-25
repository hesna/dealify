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
}
