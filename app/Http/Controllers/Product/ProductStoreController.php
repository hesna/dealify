<?php

namespace App\Http\Controllers\Product;

use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductStoreController
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
     * ProductStoreController constructor.
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
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $validated = $this->request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric|between:10,500',
        ]);

        return response()->json(
            $this->productService->createProduct($validated),
            Response::HTTP_CREATED
        );
    }
}
