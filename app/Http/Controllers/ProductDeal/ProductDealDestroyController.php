<?php

namespace App\Http\Controllers\ProductDeal;

use App\Models\Product;
use App\Services\ProductDealsService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductDealDestroyController
 * @package App\Http\Controllers
 */
class ProductDealDestroyController
{
    /**
     * @var ProductDealsService
     */
    private $productDealsService;

    /**
     * ProductDealDestroyController constructor.
     * @param ProductDealsService $productDealsService
     * @return void
     */
    public function __construct(ProductDealsService $productDealsService)
    {
        $this->productDealsService = $productDealsService;
    }

    /**
     * destroys all deals of a product
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        $this->productDealsService->deleteProductDeals($product);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
