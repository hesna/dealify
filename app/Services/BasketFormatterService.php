<?php
namespace App\Services;

use App\Classes\Basket;
use App\Classes\BasketProduct;

class BasketFormatterService
{
	protected $basketService;

	public function __construct(BasketService $basketService)
	{
		$this->basketService = $basketService;
	}

	public function toFrindlyArray(Basket $basket)
	{
		$trp = $this->basketService->getTotalRawPrice($basket);
		$tp = $this->basketService->getTotalPrice($basket);

		return [
			'products' => $this->getSimpleProductsList($basket),
			'totalRawPrice' => $trp,
			'totalPrice' => $tp,
			'total discount' => $trp - $tp
		];
	}

	protected function getSimpleProductsList(Basket $basket)
	{
		$output = [];
		foreach ($basket->getRawProducts() as $product) {
			$output[] = [
				'name' => $product->getName(),
				'price' => $product->getPrice(),
				'count' => $product->getCount(),
			];
		}

		return $output;
	}
}