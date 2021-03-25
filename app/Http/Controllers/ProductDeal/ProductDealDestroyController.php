<?php

namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Models\Product;
use Illuminate\Http\Response;

/**
 * Class ProductDealDestroyController
 * @package App\Http\Controllers
 */
class ProductDealDestroyController
{
    /**
     * @var ProductDealsServiceInterface
     */
    private ProductDealsServiceInterface $productDealsService;

    /**
     * ProductDealDestroyController constructor.
     * @param ProductDealsServiceInterface $productDealsService
     * @return void
     */
    public function __construct(ProductDealsServiceInterface $productDealsService)
    {
        $this->productDealsService = $productDealsService;
    }

    /**
     * destroys all deals of a product
     *
     * @param Product $product
     * @return Response
     */
    public function __invoke(Product $product): Response
    {
        $this->productDealsService->deleteProductDeals($product);

        return response()->noContent();
    }
}
