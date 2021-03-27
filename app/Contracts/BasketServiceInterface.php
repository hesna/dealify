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
     * @return Basket
     */
    public function checkout(array $productIds): Basket;

    /**
     * @param Basket $basket
     * @return float|int
     */
    public function getTotalRawPrice(Basket $basket): float|int;

    /**
     * @param Basket $basket
     * @return float|int
     */
    public function getTotalPrice(Basket $basket): float|int;
}
