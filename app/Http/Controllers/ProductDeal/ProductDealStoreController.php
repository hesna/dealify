<?php

namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Models\Product;
use App\Rules\ArrayValuesForKeyAreUnique;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductDealStoreController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ProductDealsServiceInterface
     */
    private $pdService;

    /**
     * ProductDealStoreController constructor.
     * @param Request $request
     * @param ProductDealsServiceInterface $pdService
     * @return void
     */
    public function __construct(Request $request, ProductDealsServiceInterface $pdService)
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
        $validated = $this->request->validate([
            'deals' => ['required', 'array', new ArrayValuesForKeyAreUnique('number_of_products')],
            'deals.*.price' => 'required|numeric|min:10',
            'deals.*.number_of_products' => 'required|numeric|between:2,50',
        ]);

        $this->pdService->setProductDeals($product, $validated['deals']);

        return response()->json($product->deals, Response::HTTP_CREATED);
    }
}
