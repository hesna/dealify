<?php
namespace App\Classes;

use App\Models\Deal;
use App\Models\Product;

/**
 * represents a product in basket
 */
class BasketProduct
{
	protected $code;
	protected $name;
	protected $price;
	protected $count;

	function __construct($code, $name, $price, $count)
	{
		$this->code = $code;
		$this->name = $name;
		$this->price = $price;
		$this->count = $count;
	}

	public function getCode()
	{
		return $this->code;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getPrice()
	{
		return $this->price;
	}	

	public function getCount()
	{
		return $this->count;
	}

	public function setCount(int $number)
	{
		$this->count = $number;
	}	
}