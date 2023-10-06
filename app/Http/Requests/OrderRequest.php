<?php

namespace App\Http\Requests;

class OrderRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'customer_id' => 'required',
            'partner_id' => 'required',
            'total_price' => 'required',
            'item_count' => 'required',
            'address' => '',
            'longitude' => 'required',
            'latitude' => 'required',
            'payment_type' => 'required',
            'order_items' => ''
        ];
    }
}
