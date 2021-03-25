<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package App\Http\Controllers
 */
class ProductController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        if (($validator = $this->productRequestValidator($request))->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json(Product::create($request->all()), Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        if (($validator = $this->productRequestValidator($request))->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $product->update($request->all());

        return response()->json($product);
    }

    /**
     * @param Request $request
     * @return ValidatorContract
     */
    protected function productRequestValidator(Request $request): ValidatorContract
    {
        return Validator::make($request->all(), [
            'name' => 'required|max:255',
            'price' => 'required|numeric|between:10,500',
        ]);
    }
}
