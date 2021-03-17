<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;

class BasketFormatterService
{
	public function toFrindlyArray(Basket $basket)
	{
		return [
			'products' => $this->getSimpleProductsList($basket),
			'totalPrice' => $basket->getTotalPrice(),
		];
	}

	protected function getSimpleProductsList(Basket $basket)
	{
		$output = [];
		foreach ($basket->getProducts() as $product) {
			$output[] = [
				'name' => $product->getName(),
				'price' => $product->getPrice(),
				'count' => $product->getCount(),
			];
		}

		return $output;
	}
}