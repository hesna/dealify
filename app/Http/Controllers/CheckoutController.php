<?php

namespace App\Http\Controllers;

use App\Contracts\BasketServiceInterface;
use App\Contracts\BasketFormatterServiceInterface;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class CheckoutController extends Controller
{
    /**
     * Creates a basket and calculates the price
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(
        Request $request, 
        BasketServiceInterface $basketService, 
        BasketFormatterServiceInterface $basketFormatter
    ) {
        $validator = Validator::make($request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|numeric|exists:products',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $basket = $basketService->checkout(Arr::pluck($request->get('products'), 'id'));

        return $basketFormatter->toFrindlyArray($basket);
    }
}
