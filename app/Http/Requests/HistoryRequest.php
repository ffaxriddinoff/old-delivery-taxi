<?php

namespace App\Http\Requests;

class HistoryRequest extends BaseRequest {
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
            'order_id' => 'required',
            'order_type' => 'required|numeric',
            'fare' => '',
            'minute' => '',
            'path' => '',
            'status' => 'required',
            'luggage' => ''
        ];
    }
}
