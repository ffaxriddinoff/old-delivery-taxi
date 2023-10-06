<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class PartnerRequest extends BaseRequest {
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
            'name' => ['required'],
            'username' => ['required', Rule::unique('partners')
                ->ignore($this->route('partner'))],
            'password' => 'nullable|min:6',
            'start_time' => '',
            'end_time' => '',
            'address' => '',
            'card' => '',
            'phone' => '',
            'img' => '',
            'longitude' => '',
            'latitude' => '',
            'open' => '',
            'district_id' => ''
        ];
    }
}
