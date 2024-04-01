<?php

namespace App\Http\Requests;

use App\Traits\response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateLocationRequest extends FormRequest
{
    use response;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lat' => "required|numeric|between:-90,90",
            'long' => "required|numeric|between:-180,180",
        ];
    }

    public function messages()
    {
        return [
            'lat.required' => 'خط العرض مطلوب',
            'lat.between' => 'خط العرض يجب ان يكون بين -90 و  90 درجه',
            'long.required' => 'خط الطول مطلوب',
            'long.between' => 'خط الطول يجب ان يكون بين -180 و  180 درجه',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->final_response(
            success: false,
            message: $validator->errors()->first(),
            code: 422
        ));
    }
}
