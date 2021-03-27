<?php
namespace App\Contracts;

use App\Classes\Basket;

/**
 * Interface BasketFormatterServiceInterface
 * @package App\Contracts
 */
interface BasketFormatterServiceInterface
{
    /**
     * @param Basket $basket
     * @return array
     */
    public function toFriendlyArray(Basket $basket): array;
}
