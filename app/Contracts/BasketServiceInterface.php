<?php
namespace App\Contracts;

use App\Classes\Basket;

/**
 * Interface BasketServiceInterface
 * @package App\Contracts
 */
interface BasketServiceInterface
{
    /**
     * @param array $productIds
     * @return mixed
     */
    public function checkout(array $productIds);

    /**
     * @param Basket $basket
     * @return mixed
     */
    public function getTotalRawPrice(Basket $basket);

    /**
     * @param Basket $basket
     * @return mixed
     */
    public function getTotalPrice(Basket $basket);
}
