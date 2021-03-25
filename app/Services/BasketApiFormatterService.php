<?php
namespace App\Services;

use App\Classes\Basket;
use App\Contracts\BasketFormatterServiceInterface;

/**
 * Class BasketApiFormatterService
 * @package App\Services
 */
class BasketApiFormatterService implements BasketFormatterServiceInterface
{
    /**
     * @var BasketService
     */
    protected $basketService;

    /**
     * BasketApiFormatterService constructor.
     * @param BasketService $basketService
     * @return void
     */
    public function __construct(BasketService $basketService)
    {
        $this->basketService = $basketService;
    }

    /**
     * @param Basket $basket
     * @return array
     */
    public function toFriendlyArray(Basket $basket): array
    {
        $trp = $this->basketService->getTotalRawPrice($basket);
        $tp = $this->basketService->getTotalPrice($basket);

        return [
            'products' => $this->getSimpleProductsList($basket),
            'applied_deals' => $this->getSimpleDealsList($basket),
            'total_raw_price' => $trp,
            'total_price' => $tp,
            'total_discount' => $trp - $tp
        ];
    }

    /**
     * @param Basket $basket
     * @return array
     */
    protected function getSimpleProductsList(Basket $basket): array
    {
        $output = [];
        foreach ($basket->getRawProducts() as $product) {
            $output[] = [
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'count' => $product->getCount(),
            ];
        }

        return $output;
    }

    /**
     * @param Basket $basket
     * @return array
     */
    protected function getSimpleDealsList(Basket $basket): array
    {
        $output = [];
        foreach ($basket->getAppliedDeals() as $deal) {
            $output[] = [
                'product_name' => $deal['product_name'],
                'number_of_products' => $deal['number_of_products'],
                'price' => $deal['price'],
                'match_times' => $deal['count'],
            ];
        }

        return $output;
    }
}
