<?php
namespace App\Classes;

/**
 * holds data of the current checked-out basket
 */
class Basket
{

    /**
     * @var array of BasketProducts
     */
    protected $products = [];

    /**
     * plain list of checked-out products
     *
     * @var array of ids
     */
    protected $rawProducts = [];

    /**
     * @var array of Deals
     */
    protected $appliedDeals = [];


    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }


    /**
     * @return array
     */
    public function getAppliedDeals(): array
    {
        return $this->appliedDeals;
    }


    /**
     * @return array
     */
    public function getRawProducts(): array
    {
        return $this->rawProducts;
    }


    /**
     * @return array
     */
    public function getRawProductCodes(): array
    {
        $codes = [];
        foreach ($this->rawProducts as $product) {
            for ($i = 0; $i < $product->getCount(); $i++) {
                $codes[] = $product->getCode();
            }
        }

        return $codes;
    }


    /**
     * @param BasketProduct $bp
     */
    public function add(BasketProduct $bp): void
    {
        $this->products[] = $bp;
    }


    /**
     * @param BasketProduct $bp
     */
    public function addRaw(BasketProduct $bp): void
    {
        $this->rawProducts[] = $bp;
    }


    /**
     * @param $deal
     */
    public function addAppliedDeal($deal): void
    {
        $this->appliedDeals[] = $deal;
    }
}
