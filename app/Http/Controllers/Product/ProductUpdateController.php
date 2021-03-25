<?php

namespace App\Http\Controllers\Product;

use App\Contracts\ProductServiceInterface;
use App\Http\Requests\StoreUpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

/**
 * Class ProductUpdateController
 * @package App\Http\Controllers
 */
class ProductUpdateController
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
     * ProductUpdateController constructor.
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
     * Update the specified resource in storage.
     *
     * @param Product $product
     * @return ProductResource
     */
    public function __invoke(Product $product): ProductResource
    {
        $this->productService->updateProduct($product, $this->request->validated());

        return ProductResource::make($product);
    }
}
