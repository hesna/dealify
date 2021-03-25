<?php

namespace Tests\Unit;

use App\Rules\ArrayValuesForKeyAreUnique;
use Tests\TestCase;

class ArrayValuesForKeyAreUniqueTest extends TestCase
{
    public function test_true_if_uniquekey_is_unique()
    {
        $testArray = [
            ['unique-key' => 1, 'another-key' => 1],
            ['unique-key' => 2, 'another-key' => 1],
            ['unique-key' => 3, 'another-key' => 5],
        ];
        $validator = new ArrayValuesForKeyAreUnique('unique-key');

        self::assertTrue($validator->passes('attr', $testArray));
    }

    public function test_false_if_uniquekey_is_not_unique()
    {
        $testArray = [
            ['unique-key' => 1, 'another-key' => 1],
            ['unique-key' => 2, 'another-key' => 1],
            ['unique-key' => 3, 'another-key' => 5],
        ];
        $validator = new ArrayValuesForKeyAreUnique('another-key');

        self::assertFalse($validator->passes('attr', $testArray));
    }

    public function test_true_if_array_is_empty()
    {
        $validator = new ArrayValuesForKeyAreUnique('some-key');

        self::assertTrue($validator->passes('attr', []));
    }

    public function test_false_if_invalid_format()
    {
        $testArray = [
            ['unique-key' => 1, 'another-key' => 1],
            ['unique-key' => 2, 'another-key' => 1],
            ['some-other-key' => 3, 'another-key' => 5],
        ];
        $validator = new ArrayValuesForKeyAreUnique('unique-key');

        self::assertFalse($validator->passes('attr', $testArray));
    }

    public function test_false_if_invalid_values()
    {
        $testArray = [
            ['unique-key' => null, 'another-key' => 1],
            ['unique-key' => false, 'another-key' => 1],
            ['unique-key' => [1, 2], 'another-key' => 5],
        ];
        $validator = new ArrayValuesForKeyAreUnique('unique-key');

        self::assertFalse($validator->passes('attr', $testArray));
    }

    public function test_false_if_not_array()
    {
        $validator = new ArrayValuesForKeyAreUnique('unique-key');

        self::assertFalse($validator->passes('attr', 'some string'));
    }

    public function test_false_if_key_not_exists()
    {
        $testArray = [
            ['some-key' => 1, 'another-key' => 1],
            ['some-key' => 2, 'another-key' => 1],
            ['some-key' => 3, 'another-key' => 5],
        ];
        $validator = new ArrayValuesForKeyAreUnique('key-not-exist');

        self::assertFalse($validator->passes('attr', $testArray));
    }
}
