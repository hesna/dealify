<?php
namespace App\Services;

use App\Models\Product;

class ProductService implements \App\Contracts\ProductServiceInterface
{

	public function getProductsbyIds(array $ids)
	{
		$products = Product::select(['id', 'name', 'price'])->whereIn('id', $ids)->get();

		return $products->toArray();
	}
}