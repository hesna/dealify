<?php
namespace App\Http\Controllers\ProductDeal;

use App\Models\Product;
use App\Services\ProductDealsService;
use Illuminate\Http\JsonResponse;

class ProductDealIndexController
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
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        return response()->json($this->productDealsService->getProductDeals($product));
    }
}
