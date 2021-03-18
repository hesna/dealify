<?php
namespace App\Classes;

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
	 * plain list of checkedout products
	 * @var array of ids
	 */
	protected $rawProducts;

	/**
	 * @var array of Deals
	 */
	protected $appliedDeals;

	public function getProducts()
	{
		return $this->products;
	}

	public function getRawProducts()
	{
		return $this->rawProducts;
	}	

	public function getRawProductCodes()
	{
		$codes = [];
		foreach ($this->rawProducts as $product) {
			for ($i=0; $i < $product->getCount(); $i++) { 
				$codes[] = $product->getCode();
			}
		}

		return $codes;
	}		

	public function add(BasketProduct $bp)
	{
		$this->products[] = $bp;
	}

	public function addRaw(BasketProduct $bp)
	{
		$this->rawProducts[] = $bp;
	}
}