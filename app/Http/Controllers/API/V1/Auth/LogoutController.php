<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    use GeneralTrait;
    public function logout(){
        $user =Auth::user();
        $currentAccessToken = $user->currentAccessToken();
        $tokens = $user->tokens()->where('id', $currentAccessToken->id)->get();
        $tokens->each(function ($token) {
            $token->delete();
            });
        return $this->buildResponse(null, 'Success', 'Logged out successfully', 200);
    }


    public function logout_all_devices(){
        //user current
        $user= Auth::user();
        // Token current
        $currentAccessToken = $user->currentAccessToken();
        // Get all tokens for current user
        $otherTokens = $user->tokens()->where('id', '!=', $currentAccessToken->id)->get();

        //delete all tokens for current  without current token
        foreach ($otherTokens as $token){
            $token->delete();
        }
        return $this->buildResponse(null,'Success',  'Logged out from all devices', 200);
    }
}
