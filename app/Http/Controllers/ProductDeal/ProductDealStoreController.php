<?php

namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Http\Requests\StoreProductDealRequest;
use App\Http\Resources\ProductDealResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductDealStoreController
{
    /**
     * @var StoreProductDealRequest
     */
    private StoreProductDealRequest $request;
    /**
     * @var ProductDealsServiceInterface
     */
    private ProductDealsServiceInterface $pdService;

    /**
     * ProductDealStoreController constructor.
     * @param StoreProductDealRequest $request
     * @param ProductDealsServiceInterface $pdService
     * @return void
     */
    public function __construct(StoreProductDealRequest $request, ProductDealsServiceInterface $pdService)
    {
        $this->request = $request;
        $this->pdService = $pdService;
    }


    /**
     * resets all deals of a product each time called
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        $validated = $this->request->validated();
        $this->pdService->setProductDeals($product, $validated['deals']);

        return ProductDealResource::collection($product->deals)
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
