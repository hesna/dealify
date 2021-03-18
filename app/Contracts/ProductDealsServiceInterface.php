<?php
namespace App\Contracts;

use App\Models\Product;
use App\Classes\Basket;

interface ProductDealsServiceInterface
{
	/**
	 * @param Product
	 * @param array 
	 */
	public function setProductDeals(Product $product, array $deals);
	
	/**
	 * @param  Basket
	 * @return void
	 */
	public function applyDeals(Basket $basket);
}