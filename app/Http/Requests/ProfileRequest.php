<?php

namespace App\Http\Requests;

use App\Traits\response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'phone' => ['required', 'numeric', 'starts_with:010,012,015,011', 'max_digits:15',
                Rule::unique('drivers', 'phone')->whereNull('deleted_at')->ignore(request()->user()->id)
            ],
            'image' => ['nullable', Rule::imageFile()->max('1mb')->extensions(['png', 'jpg', 'jpeg', 'webp'])],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'name.max' => 'الاسم لا يتعدي 150 حرف',
            'phone.required' => 'الهاتف مطلوب',
            'phone.starts_with' => 'الهاتف يجب ان يبدأ ب 010 او 015 او 011 او 012',
            'phone.max_digits' => 'الهاتف لا يتعدي 15 رقما',
            'phone.unique' => 'الهاتف موجود بالفعل',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw  new HttpResponseException($this->final_response(success: false, message: $validator->errors()->first(), code: 400));
    }
}
