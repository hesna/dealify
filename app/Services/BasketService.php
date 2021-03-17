<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;

/**
 * Handles the logic related to the basket
 */
class BasketService
{
	protected $productServce;

	function __construct(ProductService $productServce)
	{
		$this->productServce = $productServce;
	}

	public function checkout(array $productIds)
	{
		$basket = new Basket();
		$this->fillBasket($basket, $productIds);
		// $this->applyDeals($basket);

		return $basket;
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
			$basket->add($bp);
		}
	}
}