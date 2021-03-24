<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

/**
 * Class ArrayValuesForKeyAreUnique
 * Given an array [
 * [
 *      a => value1,
 *      b => value2,
 * ],
 * [
 *      a => value3,
 *      b => value4,
 * ]
 * ]
 * This rule makes sure different values for a given key (like a) are not duplicated
 * @package App\Rules
 */
class ArrayValuesForKeyAreUnique implements Rule
{
    /**
     * @var string the key that its values should be unique
     */
    private $key;

    /**
     * Create a new rule instance.
     *
     * @param $key
     * @return void
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $numbers = Arr::pluck($value, 'number_of_products');

        return count($numbers) === count(array_unique($numbers));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return "the value of $this->key must be unique within the given input.";
    }
}
