<?php
namespace App\Contracts;

/**
 * Interface ProductServiceInterface
 * @package App\Contracts
 */
interface ProductServiceInterface
{
    /**
     * @param  array of product ids
     * @return array of product arrays
     */
    public function getProductsByIds(array $ids): array;
}
