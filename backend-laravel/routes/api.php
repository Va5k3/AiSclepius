<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PredictionController; 

// Tvoja nova API login ruta
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        // Kreiramo Sanctum token za korisnika
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Pogrešni podaci'
    ], 401);
});

//heart-check
Route::post('/predict-heart', [PredictionController::class, 'predictHeart'])->name('predict.heart');
//diabetes-check
Route::post('/predict-diabetes',[PredictionController::class,'predictDiabetes'])->name('predict.diabetes');
