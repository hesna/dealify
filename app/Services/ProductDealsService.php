<?php
namespace App\Services;

use App\Contracts\ArrayCombinationsServiceInterface;
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
    private ArrayCombinationsService $arrayCombinationsService;

    /**
     * ProductDealsService constructor.
     * @param ArrayCombinationsServiceInterface $arrayCombinationsService
     * @return void
     */
    public function __construct(ArrayCombinationsServiceInterface $arrayCombinationsService)
    {
        $this->arrayCombinationsService = $arrayCombinationsService;
    }

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
     * @param Product $product
     * @return int number of deleted deals
     */
    public function deleteProductDeals(Product $product): int
    {
        return Deal::where('product_id', $product->id)->delete();
    }

    /**
     * @param Product $product
     * @return mixed
     */
    public function getProductDeals(Product $product): mixed
    {
        return $product->deals;
    }

    /**
     * this method only loads deals that are applicable to given products
     * that's how we deal with thousands of deals!
     *
     * @param Basket $basket
     * @return array of deals
     */
    public function getBasketApplicableDeals(Basket $basket): array
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
        $query = Deal::select(['id', 'product_id', 'number_of_products', 'price']);
        foreach ($dealables as $productId => $count) {
            $query->orWhere(function ($query) use ($productId, $count) {
                $query->where('product_id', $productId)->where('number_of_products', '<=', $count);
            });
        }

        foreach ($query->get()->toArray() as $deal) {
            $deals[$deal['product_id']][] = $deal;
        }

        return $deals;
    }

    /**
     * @param Basket $basket
     * @param array $deals
     */
    public function applyDealsOnBasket(Basket $basket, array $deals) : void
    {
        foreach ($basket->getRawProducts() as $product) {
            if (array_key_exists($product->getCode(), $deals)) {
                $this->applyDealsOnProduct(
                    $basket,
                    clone $product,
                    $deals[$product->getCode()]
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
    protected function applyDealsOnProduct(Basket $basket, BasketProduct $product, array $deals): void
    {
        if (empty($deals)) {
            return;
        }
        $deals = $this->getMostRewardingDealsCombination($product, $deals);
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
     * Here we use a variation of greedy algorithm to recognize best possible deals combination
     * to be applied on basket for users to profit the most
     *
     * @param BasketProduct $product
     * @param array $deals
     * @return array
     */
    private function getMostRewardingDealsCombination(BasketProduct $product, array $deals): array
    {
        $allCombinations = $this->arrayCombinationsService->getCombinations($deals);
        $totalPrices = [];
        foreach ($allCombinations as $key => $combination) {
            $tempProduct = clone $product;
            $totalPrices[$key] = 0;
            foreach ($combination as $deal) {
                if ($tempProduct->getCount() < $deal['number_of_products']) {
                    if ($tempProduct->getCount() > 0) {
                        $totalPrices[$key] += $tempProduct->getPrice() * $tempProduct->getCount();
                    }
                    continue;
                }
                $totalPrices[$key] += $this->getTotalPriceForDeal($tempProduct, $deal);
            }
        }
        $bestCombination = array_keys($totalPrices, min($totalPrices));

        return $allCombinations[array_pop($bestCombination)];
    }

    /**
     * @param BasketProduct $product
     * @param array $deal
     * @return int
     */
    private function getTotalPriceForDeal(BasketProduct $product, array $deal): int
    {
        $totalPrice = 0;
        while ($product->getCount() >= $deal['number_of_products']) {
            $product->setCount($product->getCount() - $deal['number_of_products']);
            $totalPrice += $deal['price'];
        }

        return $totalPrice;
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
}
