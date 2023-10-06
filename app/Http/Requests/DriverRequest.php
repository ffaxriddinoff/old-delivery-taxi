<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class DriverRequest extends BaseRequest {
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
        $unique = Rule::unique('drivers')->ignore($this->route('driver'));

        return [
            'name' => 'required|string|max:255',
            'phone' => "required|numeric|digits:9|$unique",
            'surname' => '',
            'address' => 'required',
            'birth_date' => 'required|date_format:Y-m-d',
            'gender' => '',
            'card_number' => 'digits:16',
            'gmail' => '',
            'img' => '',
            'license' => 'required',
            'status' => '',
            'longitude' => '',
            'latitude' => '',
        ];
    }
}
