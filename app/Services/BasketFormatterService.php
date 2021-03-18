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
			'applied_deals' => $this->getSimpleDealsList($basket),
			'total_raw_price' => $trp,
			'total_price' => $tp,
			'total_discount' => $trp - $tp
		];
	}

	protected function getSimpleProductsList(Basket $basket)
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

	protected function getSimpleDealsList(Basket $basket)
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