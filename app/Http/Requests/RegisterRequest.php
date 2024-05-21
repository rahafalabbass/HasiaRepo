<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'firstName'=> ['required','string','max:10'],
            'lastName'=> ['required','string','max:20'],
            'email'=> ['required','email','max:30','unique:users'],
            'phone'=> ['required','string','max:10','min:10','required'],
            'password' =>['required','string','max:255','min:8','confirmed'],
        ];
        
    }
    public function messages()
    {
        return [
            'email.required' => 'يجب إدخال البريد الإلكتروني.',
            'email.exists' =>'المستخدم غير موجود',
            'email.regex' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'password.required' => 'يجب إدخال كلمة المرور.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.max' => 'يجب ألا تتجاوز كلمة المرور 255 حرفًا.'
        ];
    }
}
