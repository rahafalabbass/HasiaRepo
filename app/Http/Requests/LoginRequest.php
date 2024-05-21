<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'string', 'regex:/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+|\d{10}$/'],
            'password' => ['required', 'string', 'max:255', 'min:8']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'يجب إدخال البريد الإلكتروني.',
            //'email.exists' =>'المستخدم غير موجود',
            'email.regex' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'password.required' => 'يجب إدخال كلمة المرور.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.max' => 'يجب ألا تتجاوز كلمة المرور 255 حرفًا.'
        ];
    }
}
