<?php
namespace App\Services;

use App\Contracts\ProductDealsServiceInterface;
use App\Models\Deal;
use App\Models\Product;
use App\Classes\Basket;
use App\Classes\BasketProduct;

/**
 * Handles the logic related to product deals
 */
class ProductDealsService implements ProductDealsServiceInterface
{
    /**
     * @param Product $product
     * @param array
     */
    public function setProductDeals(Product $product, array $deals) : void
    {
        $dealObjs = [];
        Deal::where('product_id', $product->id)->delete();
        foreach ($deals as $deal) {
            $dealObjs[] = Deal::make($deal);
        }

        $product->deals()->saveMany($dealObjs);
    }

    /**
     * @param Basket $basket
     */
    public function applyDeals(Basket $basket) : void
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

    /**
     * @param Basket $basket
     * @param BasketProduct $product
     * @param array $deals
     */
    protected function applyDealsToProduct(Basket $basket, BasketProduct $product, array $deals): void
    {
        foreach ($deals as $deal) {
            $newProduct = new BasketProduct(
                $deal['number_of_products'] . ' * ' . $product->getCode(),
                $deal['number_of_products'] . ' * ' . $product->getName(),
                $deal['price'],
                0,
            );
            $this->applyDealToProduct($basket, $product, $newProduct, $deal);
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
     * @param Basket $basket
     * @param BasketProduct $rawProduct the original product added to the basket
     * @param BasketProduct $newProduct the virtual bundle created by the deal
     * @param array $deal
     *
     * @return void
     */
    protected function applyDealToProduct(
        Basket $basket,
        BasketProduct $rawProduct,
        BasketProduct $newProduct,
        array $deal
    ): void {
        if ($rawProduct->getCount() < $deal['number_of_products']) {
            if ($newProduct->getCount() > 0) {
                $basket->add($newProduct);
                $appliedDeal = $deal + [
                    'product_name' => $rawProduct->getName(),
                    'count' => $newProduct->getCount()
                ];
                $basket->addAppliedDeal($appliedDeal);
            }
            return;
        }
        $newProduct->setCount($newProduct->getCount()+1);
        $rawProduct->setCount($rawProduct->getCount() - $deal['number_of_products']);
        $this->applyDealToProduct($basket, $rawProduct, $newProduct, $deal);
    }

    /**
     * this method only loads deals that are applicable to given products
     * that's how we deal with thousands of deals!
     * and it sorts them by their profitability for the user
     * that's how we solve the multiple rules problem!
     *
     * @param Basket $basket
     * @return array of deals
     */
    protected function getRelatedDeals(Basket $basket): array
    {
        $deals = [];

        // we only care about products that are at least two of them in basket
        $dealables = array_filter(array_count_values($basket->getRawProductCodes()), static function ($value) {
            return $value > 1;
        });
        if (empty($dealables)) {
            return [];
        }

        // we only load deals that are applicable to the number of each product we have!
        $query = Deal::select(['product_id', 'number_of_products', 'price']);
        foreach ($dealables as $productId => $count) {
            $query->orWhere(function ($query) use ($productId, $count) {
                $query->where('product_id', $productId)->where('number_of_products', '<=', $count);
            });
        }

        // finally we sort deals based on their profit for user and group each products rules together
        foreach ($query->orderBy('unit_price')->get()->toArray() as $deal) {
            $deals[$deal['product_id']][] = $deal;
        }

        return $deals;
    }
}
