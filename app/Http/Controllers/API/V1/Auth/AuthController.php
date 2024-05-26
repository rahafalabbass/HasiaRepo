<?php

namespace App\Http\Controllers\API\V1\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Traits\GeneralTrait;


class AuthController extends Controller
{
    use GeneralTrait;

    //REGISTER
    public function register(RegisterRequest $request)
    {
        $newUser = User::create([
            'first_name' => $request->input('firstName'),
            'last_name' => $request->input('lastName'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => 'subscriper',
            'password' => bcrypt($request->input('password')),
        ]);

        $newUser->name = $newUser->first_name . ' ' . $newUser->last_name;
        $newUser->makeHidden(['created_at', 'updated_at', 'first_name', 'last_name']);
        $newUser->token = $newUser->createToken('MyApp')->plainTextToken;
        return $this->buildResponse($newUser,'Success','register successfully', 200);
    }

    //LOGIN

    public function login(LoginRequest $request)
    {

        $user =User::where('email', $request->input('email'))
        ->orWhere('phone',$request->input('email'))->first();

        if (!$user) {
            return $this->buildResponse('null', 'Error', 'لايوجد مستخدم', 404);
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return $this->buildResponse('null', 'Error', 'يرجى تأكد من صحة البريد  الالكتروني وكلمة السر', 404);
        }
        
       // $user->tokens()->delete();
        $user->token = $user->createToken('token')->plainTextToken;
        $user->role = trim($user->role);
        $user->name = $user->first_name . ' ' . $user->last_name;  
        $user->makeHidden(['created_at', 'updated_at', 'first_name', 'last_name','email_verified_at']);
        return $this->buildResponse($user, 'Success', 'تم تسجيل الدخول بنجاح', 200);
    }
}
