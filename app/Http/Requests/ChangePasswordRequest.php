<?php

namespace App\Http\Requests;

use App\Traits\response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
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
            'old_password' => "required",
            'password' => "required|string|max:20|min:5",
            'password_confirmation' => "required|same:password",
        ];
    }

    public function messages()
    {
        return [
            'old_password.required' => 'كلمة المرور القديمة مطلوبه',
            'password.required' => 'كلمة المرور الجديدة مطلوبه',
            'password.max' => 'كلمة المرور الجديدة لا تتعدي 20 حرف',
            'password.min' => 'كلمة المرور الجديدة لا تقل  عن 5 حروف',
            'password_confirmation.required' => 'تأكيد كلمه المرور مطلوب',
            'password_confirmation.same' => 'كلمة المرور غير مطابقة',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->old_password != null && !Hash::check($this->old_password, auth()->user()->password)) {
                $validator->errors()->add('old_password', 'كلمة المرور خطأ');
            }
        });
    }

    public function failedValidation(Validator $validator)
    {
        throw  new HttpResponseException($this->final_response(
            success: false, message: $validator->errors()->first(), code: 400
        ));
    }
}
