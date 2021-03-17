<?php
namespace App\Classes;

use App\Models\Deal;
use App\Models\Product;

/**
 * holds data of the current checkedout basket
 */
class Basket
{
	/**
	 * @var array of BasketProducts
	 */
	protected $products;

	/**
	 * @var array of Deals
	 */
	protected $appliedDeals;

	public function getProducts()
	{
		return $this->products;
	}

	public function add(BasketProduct $bp)
	{
		$this->products[] = $bp;
	}

	public function getTotalPrice()
	{
		$totalPrice = 0;
		foreach ($this->products as $product) {
			$totalPrice += $product->getPrice() * $product->getCount();
		}

		return $totalPrice;
	}
}