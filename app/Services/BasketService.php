<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;

/**
 * Handles the logic related to the basket
 */
class BasketService
{
	protected $dealService;
	protected $productServce;

	function __construct(ProductService $productServce, ProductDealsService $dealService)
	{
		$this->dealService = $dealService;
		$this->productServce = $productServce;
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
		$products = $this->productServce->getProductsbyIds($productIds);
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