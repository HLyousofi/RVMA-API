<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class LoginController extends Controller
{
    public function login(Request $request) {
        
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        if(Auth::attempt($fields)){
           
            $user = Auth::user();
            
           

            $token = $user->createToken('admin-token', expiresAt:now()->addHours(2))->plainTextToken;
            return response([   'succes' => true,
                                'name' => $user->name,
                                'accessToken' => $token,
                                // 'rights' => json_decode($user->right),
                                // 'role' => $user->role
                            
                            ]);
        }

        return response([   'succes' => false,
                            'error' => "error email or password"]);
    }

    public function logout(Request $request) {
        $user = Auth::user();
        $user->tokens()->delete();
    }
}
