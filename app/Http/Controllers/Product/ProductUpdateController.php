<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\StoreUpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;

/**
 * Class ProductUpdateController
 * @package App\Http\Controllers
 */
class ProductUpdateController
{
    /**
     * @var StoreUpdateProductRequest
     */
    private $request;
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductUpdateController constructor.
     * @param StoreUpdateProductRequest $request
     * @param ProductService $productService
     * @return void
     */
    public function __construct(StoreUpdateProductRequest $request, ProductService $productService)
    {
        $this->request = $request;
        $this->productService = $productService;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        $this->productService->updateProduct($product, $this->request->validated());

        return response()->json($product);
    }
}
