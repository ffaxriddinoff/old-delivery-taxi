<?php

namespace App\Http\Requests;

class DriverOrderRequest extends BaseRequest {
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'order_id' => '',
            'driver_id' => '',
            'name' => '',
            'phone' => '',
            'address' => '',
            'longitude' => '',
            'latitude' => '',
            'type' => '',
            'status' => ''
        ];
    }

    public function messages() {
        return [
            'phone' => "Siz allaqochon buyurtma bergansiz"
        ];
    }
}
