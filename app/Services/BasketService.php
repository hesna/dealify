<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;
use App\Contracts\ProductServiceInterface;
use App\Contracts\ProductDealsServiceInterface;

/**
 * Handles the logic related to the basket
 */
class BasketService implements \App\Contracts\BasketServiceInterface
{
	protected $dealService;
	protected $productService;

	public function __construct(ProductServiceInterface $productService, ProductDealsServiceInterface $dealService)
	{
		$this->dealService = $dealService;
		$this->productService = $productService;
	}

	public function checkout(array $productIds)
	{
		$basket = new Basket();
		$this->fillBasket($basket, $productIds);
		$this->dealService->applyDeals($basket);

		return $basket;
	}

	public function getTotalRawPrice(Basket $basket)
	{
		return $this->calculatePrice($basket->getRawProducts());
	}

	public function getTotalPrice(Basket $basket)
	{
		return $this->calculatePrice($basket->getProducts());
	}	

	protected function calculatePrice($products)
	{
		$totalPrice = 0;
		foreach ($products as $product) {
			$totalPrice += $product->getPrice() * $product->getCount();
		}

		return $totalPrice;
	}

	protected function fillBasket(Basket $basket, array $productIds)
	{
		$products = $this->productService->getProductsbyIds($productIds);
		$occurances = array_count_values($productIds);
		foreach ($products as $product) {
			$bp = new BasketProduct(
				$product['id'],
				$product['name'],
				$product['price'],
				$occurances[$product['id']]
			);
			$basket->addRaw($bp);
		}
	}
}