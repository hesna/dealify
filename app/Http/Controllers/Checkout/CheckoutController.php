<?php

namespace App\Http\Controllers\Checkout;

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
     * @var Request
     */
    private $request;
    /**
     * @var BasketServiceInterface
     */
    private $basketService;
    /**
     * @var BasketFormatterServiceInterface
     */
    private $basketFormatter;

    /**
     * CheckoutController constructor.
     * @param Request $request
     * @param BasketServiceInterface $basketService
     * @param BasketFormatterServiceInterface $basketFormatter
     * @return void
     */
    public function __construct(
        Request $request,
        BasketServiceInterface $basketService,
        BasketFormatterServiceInterface $basketFormatter
    ) {
        $this->request = $request;
        $this->basketService = $basketService;
        $this->basketFormatter = $basketFormatter;
    }


    /**
     * Creates a basket and calculates the price
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $validator = Validator::make($this->request->all(), [
            'products' => 'required|array',
            'products.*.id' => 'required|numeric|exists:products',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $basket = $this->basketService->checkout(Arr::pluck($this->request->get('products'), 'id'));

        return response()->json($this->basketFormatter->toFriendlyArray($basket));
    }
}
