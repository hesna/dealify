<?php
namespace App\Contracts;

use App\Classes\Basket;

interface BasketFormatterServiceInterface
{
	public function toFrindlyArray(Basket $basket);
}