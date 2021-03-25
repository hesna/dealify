<?php

namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Http\Requests\StoreProductDealRequest;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductDealStoreController
{
    /**
     * @var StoreProductDealRequest
     */
    private $request;
    /**
     * @var ProductDealsServiceInterface
     */
    private $pdService;

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

        return response()->json($product->deals, Response::HTTP_CREATED);
    }
}
