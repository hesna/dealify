<?php

namespace App\Contracts;

/**
 * Class ArrayCombinationsService
 * @package App\Services
 */
interface ArrayCombinationsServiceInterface
{
    /**
     * @param array $input
     * @return array
     */
    public function getCombinations(array $input): array;
}
