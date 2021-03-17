<?php
namespace App\Services;

use App\Models\Deal;
use App\Models\Product;

/**
 * Handles the logic related to productdeals
 */
class ProductDealsService
{
	/**
	 * @param Product
	 * @param array 
	 */
	public function setProductDeals(Product $product, array $deals)
	{
        Deal::where('product_id', $product->id)->delete();
        foreach ($deals as $deal) {
            $dealObjs[] = Deal::make($deal);
        }

        $product->deals()->saveMany($dealObjs);
	}
}