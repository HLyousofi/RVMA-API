<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class AuthController extends Controller
{
    public function login(Request $request) {
        
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            if (Auth::attempt($fields)) {
                $user = Auth::user();
        
                try {
                    // Attempt to create a token
                    $token = $user->createToken('admin-token', expiresAt:now()->addHours(2))->plainTextToken;
                    return response([
                        'success' => true,
                        'name' => $user->name,
                        'accessToken' => $token,
                        // 'rights' => json_decode($user->right),
                        // 'role' => $user->role
                    ]);
                } catch (\Exception $e) {
                    // Handle any errors that occur during the token creation
                    return response([
                        'success' => false,
                        'error' => 'An error occurred while creating the token: ' . $e->getMessage()
                    ], 500);
                }
            } else {
                // If authentication fails
                return response([
                    'success' => false,
                    'error' => 'error email or password'
                ]);
            }
        } catch (\Exception $e) {
            // Handle any errors that occur during the authentication attempt
            return response([
                'success' => false,
                'error' => 'An error occurred during authentication: ' . $e->getMessage()
            ], 500);
        }
    }

    


    public function logout(Request $request) {
        // Récupérer l’utilisateur authentifié
        $user = $request->user();

        // Révoquer tous les tokens de l’utilisateur (ou juste le token actuel)
        $user->tokens()->delete(); // Supprime tous les tokens
        // Pour supprimer uniquement le token actuel :
        // $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ], 200);
    }
}
