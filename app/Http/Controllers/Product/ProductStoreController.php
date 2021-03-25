<?php

namespace App\Http\Controllers\Product;

use App\Contracts\ProductServiceInterface;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Http\Resources\ProductResource;

/**
 * Class ProductStoreController
 * @package App\Http\Controllers\Product
 */
class ProductStoreController
{
    /**
     * @var StoreUpdateProductRequest
     */
    private StoreUpdateProductRequest $request;
    /**
     * @var ProductServiceInterface
     */
    private ProductServiceInterface $productService;

    /**
     * ProductStoreController constructor.
     * @param StoreUpdateProductRequest $request
     * @param ProductServiceInterface $productService
     * @return void
     */
    public function __construct(StoreUpdateProductRequest $request, ProductServiceInterface $productService)
    {
        $this->request = $request;
        $this->productService = $productService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return ProductResource
     */
    public function __invoke(): ProductResource
    {
        $product = $this->productService->createProduct($this->request->validated());

        return ProductResource::make($product);
    }
}
