<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;
use App\Contracts\BasketServiceInterface;
use App\Contracts\ProductServiceInterface;
use App\Contracts\ProductDealsServiceInterface;

/**
 * Handles the logic related to the basket
 */
class BasketService implements BasketServiceInterface
{
    /**
     * @var ProductDealsServiceInterface
     */
    protected ProductDealsServiceInterface $dealService;
    /**
     * @var ProductServiceInterface
     */
    protected ProductServiceInterface $productService;

    /**
     * BasketService constructor.
     * @param ProductServiceInterface $productService
     * @param ProductDealsServiceInterface $dealService
     * @return void
     */
    public function __construct(ProductServiceInterface $productService, ProductDealsServiceInterface $dealService)
    {
        $this->dealService = $dealService;
        $this->productService = $productService;
    }

    /**
     * @param array $productIds
     * @return Basket
     */
    public function checkout(array $productIds): Basket
    {
        $basket = new Basket();
        $this->fillBasket($basket, $productIds);
        $this->dealService->applyDeals($basket);

        return $basket;
    }

    /**
     * @param Basket $basket
     * @return float|int
     */
    public function getTotalRawPrice(Basket $basket)
    {
        return $this->calculatePrice($basket->getRawProducts());
    }

    /**
     * @param Basket $basket
     * @return float|int
     */
    public function getTotalPrice(Basket $basket)
    {
        return $this->calculatePrice($basket->getProducts());
    }

    /**
     * @param $products
     * @return float|int
     */
    protected function calculatePrice($products)
    {
        $totalPrice = 0;
        foreach ($products as $product) {
            $totalPrice += $product->getPrice() * $product->getCount();
        }

        return $totalPrice;
    }

    /**
     * @param Basket $basket
     * @param array $productIds
     */
    protected function fillBasket(Basket $basket, array $productIds): void
    {
        $products = $this->productService->getProductsByIds($productIds);
        $occurrences = array_count_values($productIds);
        foreach ($products as $product) {
            $bp = new BasketProduct(
                $product['id'],
                $product['name'],
                $product['price'],
                $occurrences[$product['id']]
            );
            $basket->addRaw($bp);
        }
    }
}
