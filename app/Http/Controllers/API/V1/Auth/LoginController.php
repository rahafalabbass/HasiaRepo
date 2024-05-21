<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Areas;
use App\Models\earths;
use App\Models\subscriptions;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use GeneralTrait;
    public function login(LoginRequest $request)
    {

        $user =User::where('email', $request->input('email'))
        ->orWhere('phone',$request->input('email'))->first();

        if (!$user) {
            return $this->authResponse('null', 'ًError', 'لايوجد مستخدم', 404);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return $this->authResponse('null', 'Error', 'يرجى تأكد من صحة البريد  الالكتروني وكلمة السر', 404);
        }
       
        $user->token = $user->createToken('token')->plainTextToken;
        $user->role = trim($user->role);
        $user->name = $user->first_name . ' ' . $user->last_name;  
        $user->makeHidden(['created_at', 'updated_at', 'first_name', 'last_name','email_verified_at']);
        return $this->authResponse($user, 'Success', 'done login successfully', 200);
    }
}
