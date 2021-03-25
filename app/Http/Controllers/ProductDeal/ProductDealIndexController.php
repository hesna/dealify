<?php
namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Http\Resources\ProductDealResource;
use App\Models\Product;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductDealIndexController
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
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return AnonymousResourceCollection
     */
    public function __invoke(Product $product): AnonymousResourceCollection
    {
        $deals = $this->productDealsService->getProductDeals($product);

        return ProductDealResource::collection($deals);
    }
}
