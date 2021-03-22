<?php

namespace App\Http\Controllers\ProductDeal;

use App\Contracts\ProductDealsServiceInterface;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Response;

class ProductDealStoreController
{
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ProductDealsServiceInterface
     */
    private $pdService;

    /**
     * ProductDealStoreController constructor.
     * @param Request $request
     * @param ProductDealsServiceInterface $pdService
     * @return void
     */
    public function __construct(Request $request, ProductDealsServiceInterface $pdService)
    {
        $this->request = $request;
        $this->pdService = $pdService;
    }


    /**
     * resets all deals of a product each time called
     *
     * @param Product $product
     * @return JsonResponse
     */
    public function __invoke(Product $product): JsonResponse
    {
        if (!empty($errors = $this->getDealsRequestErrors($this->request))) {
            return response()->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $this->pdService->setProductDeals($product, $this->request->get('deals'));

        return response()->json($product->deals, Response::HTTP_CREATED);
    }

    /**
     * @param Request $request
     * @return array|MessageBag|string[][]
     */
    private function getDealsRequestErrors(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'deals' => 'required|array',
            'deals.*.price' => 'required|numeric|min:10',
            'deals.*.number_of_products' => 'required|numeric|between:2,50',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }

        $numbers = Arr::pluck($request->get('deals'), 'number_of_products');
        if (count($numbers) !== count(array_unique($numbers))) {
            return ["deals" => ["the value of number_of_products must be unique within the given input."]];
        }

        return [];
    }
}
