<?php

namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

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
        if (($validator = $this->productRequestValidator($this->request))->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->productService->updateProduct($product, $this->request->all());

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @return ValidatorContract
     */
    private function productRequestValidator(Request $request): ValidatorContract
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:255',
            'price' => 'required|numeric|between:10,500',
        ]);
    }
}
