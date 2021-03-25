<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\StoreUpdateProductRequest;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductStoreController
 * @package App\Http\Controllers\Product
 */
class ProductStoreController
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
     * ProductStoreController constructor.
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
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $product = $this->productService->createProduct($this->request->validated());

        return response()->json($product, Response::HTTP_CREATED);
    }
}
