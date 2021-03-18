<?php
namespace App\Contracts;

interface ProductServiceInterface
{

	/**
	 * @param  array of product ids
	 * @return array of product arrays
	 */
	public function getProductsbyIds(array $ids);
}