<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ProductUpdateController
 * @package App\Http\Controllers
 */
class ProductUpdateController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ProductService
     */
    private $productService;

    /**
     * ProductUpdateController constructor.
     * @param Request $request
     * @param ProductService $productService
     * @return void
     */
    public function __construct(Request $request, ProductService $productService)
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
        $validated = $this->request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|between:10,500',
        ]);
        $this->productService->updateProduct($product, $validated);

        return response()->json($product);
    }
}
