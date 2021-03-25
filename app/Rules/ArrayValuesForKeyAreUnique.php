<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;
use InvalidArgumentException;

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
    private string $key;

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
     * @throws InvalidArgumentException
     */
    public function passes($attribute, $value): bool
    {
        try {
            $this->validateInput($value);
        } catch (\Throwable $e) {
            // Apparently there are no simple ways to return different messages
            //  based on user input in Laravel. Currently, the validator simply returns false
            // if data is not in valid format.
            return false;
        }
        $values = Arr::pluck($value, $this->key);

        return count($values) === count(array_unique($values));
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

    /**
     * @param array $input
     * @return void
     * @throws InvalidArgumentException
     */
    private function validateInput(array $input): void
    {
        if (empty($input)) {
            return;
        }

        if (count(Arr::collapse($input)) !== count(Arr::first($input))) {
            throw new InvalidArgumentException('the given array is not in expected format.');
        }

        if (!array_key_exists($this->key, Arr::first($input))) {
            throw new InvalidArgumentException('the given array does not have the expected key.');
        }

        if (!empty(array_intersect(
            ['array', 'object', 'resource', 'NULL', 'unknown type'],
            array_map('gettype', Arr::pluck($input, $this->key))
        ))) {
            throw new InvalidArgumentException('only string, boolean and numeric values are allowed.');
        }
    }
}
