<?php
namespace App\Services;

use App\Models\Deal;
use App\Models\Product;
use App\Classes\Basket;
use App\Classes\BasketProduct;

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

	public function applyDeals(Basket $basket)
	{
		$relatedDeals = $this->getRelatedDeals($basket);
		
		foreach ($basket->getRawProducts() as $product) {
			if (array_key_exists($product->getCode(), $relatedDeals)) {
				$this->applyDealsToProduct(
					$basket, 
					clone $product, 
					$relatedDeals[$product->getCode()]
				);
			} else {
				$basket->add($product);
			}		
		}
	}

	protected function applyDealsToProduct(Basket $basket, BasketProduct $product, array $deals)
	{
		foreach ($deals as $deal) {
			$newProduct = new BasketProduct(
				$deal['number_of_products'] . ' * ' . $product->getCode(),
				$deal['number_of_products'] . ' * ' . $product->getName(),
				$deal['price'],
				0,
			);			
			$this->applyDealToProduct($basket, $product, $newProduct, $deal['number_of_products']);
		}

		if ($product->getCount() > 0) {
			$basket->add($product);
		}
	}

	/**
	 * The real magic happens here. 
	 * new product bundles are created and added to the basket
	 * the quantity of original products are reduced
	 * 
	 * @param  Basket
	 * @param  BasketProduct the original product added to the basket
	 * @param  BasketProduct the virtual bundle created by the deal
	 * @param  integer number of products deal requires to create a bundle
	 * 
	 * @return void
	 */
	protected function applyDealToProduct(
		Basket $basket, 
		BasketProduct $rawProduct,
		BasketProduct $newProduct,
		int $requiredNumberForDeal
	) {
		if ($rawProduct->getCount() < $requiredNumberForDeal) {
			if ($newProduct->getCount() > 0) {
				$basket->add($newProduct);
			}
			return;
		}
		$newProduct->setCount($newProduct->getCount()+1);
		$rawProduct->setCount($rawProduct->getCount() - $requiredNumberForDeal);
		$this->applyDealToProduct($basket, $rawProduct, $newProduct, $requiredNumberForDeal);
	}	

	/**
	 * this method only loads deals that are applicable to given products
	 * that's how we deal with thousands of deals!
	 * 
	 * @param  Basket 
	 * 
	 * @return array of deals
	 */
	protected function getRelatedDeals(Basket $basket)
	{
		$deals = [];

		// we only care about products that are at least two of them in basket
		$dealables = array_filter(array_count_values($basket->getRawProductCodes()), function($value) {
			return $value > 1;
		});

		// we only load deals that are applicable to the number of each product we have!
		$query = Deal::select(['product_id', 'number_of_products', 'price']);
		foreach ($dealables as $productId => $count) {
			$query->orWhere(function ($query) use ($productId, $count) {
			    $query->where('product_id', $productId)->where('number_of_products', '<=', $count);
			});
		}
		
		// finally we group each products rules together
		foreach ($query->get()->toArray() as $deal) {
			$deals[$deal['product_id']][] = $deal; 
		}

		return $deals;
	}
}