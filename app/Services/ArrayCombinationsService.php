<?php

namespace App\Services;

use App\Contracts\ArrayCombinationsServiceInterface;

/**
 * Class ArrayCombinationsService
 * @package App\Services
 */
class ArrayCombinationsService implements ArrayCombinationsServiceInterface
{
    /**
     * @param array $input
     * @return array
     */
    public function getCombinations(array $input): array
    {
        return $this->calculateCombinations($input);
    }

    /**
     * @param array $input
     * @param array $combinations
     * @param array $result
     * @return array
     */
    private function calculateCombinations(array $input, $combinations = [], &$result = []): array
    {
        if (empty($input)) {
            $result[] = $combinations;
            return [];
        }
        for ($i = count($input) - 1; $i >= 0; --$i) {
            $tearedInput = $input;
            $newCombination = $combinations;
            [$omittedPart] = array_splice($tearedInput, $i, 1);
            array_unshift($newCombination, $omittedPart);
            $this->calculateCombinations($tearedInput, $newCombination, $result);
        }

        return $result;
    }
}
