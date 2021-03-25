<?php

namespace App\Http\Controllers\Checkout;

use App\Contracts\BasketServiceInterface;
use App\Contracts\BasketFormatterServiceInterface;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

/**
 * Class CheckoutController
 * @package App\Http\Controllers
 */
class CheckoutController
{
    /**
     * @var CheckoutRequest
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
     * @param CheckoutRequest $request
     * @param BasketServiceInterface $basketService
     * @param BasketFormatterServiceInterface $basketFormatter
     * @return void
     */
    public function __construct(
        CheckoutRequest $request,
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
        $validated = $this->request->validated();
        $basket = $this->basketService->checkout(Arr::pluck($validated['products'], 'id'));

        return response()->json($this->basketFormatter->toFriendlyArray($basket));
    }
}
