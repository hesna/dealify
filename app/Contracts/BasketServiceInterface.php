<?php
namespace App\Contracts;

use App\Classes\Basket;

interface BasketServiceInterface
{
	public function checkout(array $productIds);

	public function getTotalRawPrice(Basket $basket);

	public function getTotalPrice(Basket $basket);
}