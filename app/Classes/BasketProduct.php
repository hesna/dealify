<?php
namespace App\Classes;

use App\Models\Deal;
use App\Models\Product;

/**
 * represents a product in basket
 */
class BasketProduct
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var integer
     */
    protected $price;

    /**
     * @var integer
     */
    protected $count;

    /**
     * BasketProduct constructor.
     *
     * @param $code
     * @param $name
     * @param $price
     * @param $count
     *
     * @return void
     */
    public function __construct($code, $name, $price, $count)
    {
        $this->code = $code;
        $this->name = $name;
        $this->price = $price;
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $number
     */
    public function setCount(int $number): void
    {
        $this->count = $number;
    }
}
