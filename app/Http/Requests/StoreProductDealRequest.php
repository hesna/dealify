<?php

namespace App\Http\Requests;

use App\Rules\ArrayValuesForKeyAreUnique;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductDealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'deals' => ['required', 'array', new ArrayValuesForKeyAreUnique('number_of_products')],
            'deals.*.price' => 'required|numeric|min:10',
            'deals.*.number_of_products' => 'required|numeric|between:2,50',
        ];
    }
}
