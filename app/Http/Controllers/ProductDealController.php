<?php

namespace App\Http\Controllers;

use App\Models\Deal;
use App\Models\Product;
use App\Services\ProductDealsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;

class ProductDealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return $product->deals;
    }

    /**
     * resets all deals of a product each time called
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Product $product, Request $request, ProductDealsService $pdService)
    {
        if (!empty($errors = $this->getDealsRequestErrors($request))){
            return response()->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $pdService->setProductDeals($product, $request->get('deals'));

        return response()->json($product->deals, Response::HTTP_CREATED);
    }

    /**
     * destroys all deals of a product
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        Deal::where('product_id', $product->id)->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT); 
    }    

    protected function getDealsRequestErrors(Request $request)
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
        if (count($numbers) != count(array_unique($numbers))) {
            return ["deals" => ["the value of number_of_products must be unique within the given input."]];
        }

        return [];
    }    
}
