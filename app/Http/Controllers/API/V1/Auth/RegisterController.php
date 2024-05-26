<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use GeneralTrait;
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
        return $this->successResponse($newUser, 'register successfully', 200);
    }
}
