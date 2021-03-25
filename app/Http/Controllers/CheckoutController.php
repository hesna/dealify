<?php

namespace App\Http\Controllers;

use App\Contracts\BasketServiceInterface;
use App\Contracts\BasketFormatterServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CheckoutController
 * @package App\Http\Controllers
 */
class CheckoutController
{
    /**
     * Creates a basket and calculates the price
     *
     * @param Request $request
     * @param BasketServiceInterface $basketService
     * @param BasketFormatterServiceInterface $basketFormatter
     * @return JsonResponse
     */
    public function __invoke(
        Request $request,
        BasketServiceInterface $basketService,
        BasketFormatterServiceInterface $basketFormatter
    ): JsonResponse {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|numeric|exists:products',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $basket = $basketService->checkout(Arr::pluck($request->get('products'), 'id'));

        return response()->json($basketFormatter->toFriendlyArray($basket));
    }
}
