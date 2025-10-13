<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;


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
                    $accessToken = $user->createToken('access-token', ['*'], now()->addMinutes(30))->plainTextToken;

                    // Créer refresh token (7 jours)
                    $refreshToken = $user->createToken('refresh-token', ['refresh'], now()->addDays(7))->plainTextToken;
                   

                    $cookie = cookie(
                        'refresh_token',         // nom du cookie
                        $refreshToken,           // valeur
                        60*24*7,                 // durée en minutes (7 jours)
                        '/',                      // path
                        'localhost',             // domaine
                        true,                     // secure (HTTPS)
                        true,                     // httpOnly
                        false,                    // raw
                        'Strict'                  // SameSite
                    );
                    

                    return response()->json([
                        'success' => true,
                        'accessToken' => $accessToken,
                        'firstName' => $user->first_name,
                    ], 200)->cookie($cookie);

                    
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
        $user = $request->user();

        if ($user) {
            // Supprime tous les tokens (ou seulement le courant)
            $user->tokens()->delete();
        }
    
        // Supprimer le cookie refresh_token
        $forgetCookie = cookie()->forget('refresh_token');
    
        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie.'
        ])->withCookie($forgetCookie);
        
    }

 
 
    public function refreshToken(Request $request)
{
    $refreshToken = $request->cookie('refresh_token');

    if (!$refreshToken) {
        return response()->json(['success' => false, 'error' => 'Missing refresh token'], 401);
    }

    $personalAccessToken = \Laravel\Sanctum\PersonalAccessToken::findToken($refreshToken);
   
    if (!$personalAccessToken || !in_array('refresh', $personalAccessToken->abilities)) {
        return response()->json(['success' => false, 'error' => 'Invalid refresh token'], 401);
    }

     // Récupérer l'utilisateur lié
     $user = $personalAccessToken->tokenable;

    if (!$user) return response()->json(['success' => false, 'error' => 'Invalid token'], 401);

    // Génère un nouveau access token
    $newAccessToken = $user->createToken('access-token', ['*'], now()->addMinutes(30))->plainTextToken;

    return response()->json([
        'success' => true,
        'accessToken' => $newAccessToken,
        'firstName' => $user->first_name,
    ]);
}


    
}
