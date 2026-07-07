<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PredictionController; 
use App\Http\Controllers\AppointmentController; 
use App\Http\Controllers\MedicalRecordController;
use Illuminate\Support\Facades\Hash; 
use App\Models\User;                  


Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $user->tokens()->delete();// brisemo stare tokene
        // Kreiramo Sanctum token za korisnika
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'role'=> $user->role,
            'name'=>$user->name,
            'user' => $user
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'Pogrešni podaci'
    ], 401);
});

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:patient,doctor', // Prihvata samo patient ili doctor
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role, // Upisujemo ulogu u bazu
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'success' => true,
        'token' => $token,
        'role' => $user->role,
        'user' => $user
    ], 211);
});
//JAVNE RUTE


//ZASTICENE (SAMO ZA ULOGOVANE KORISNIKE)

Route::middleware('auth:sanctum')->group(function () {

    //heart-check
    Route::post('/predict-heart', [PredictionController::class, 'predictHeart'])->name('predict.heart');
    //diabetes-check
    Route::post('/predict-diabetes',[PredictionController::class,'predictDiabetes'])->name('predict.diabetes');

    Route::get('/history',[PredictionController::class, 'history']);
    Route::get('/historyId/{patientId}',[PredictionController::class, 'historyId']);
    

       
    Route::get('/appointments', [AppointmentController::class, 'index']); 
    Route::get('/appointment/{id}/room', [AppointmentController::class, 'joinRoom']);
    Route::post('/appointment/{id}/finish', [AppointmentController::class, 'finishRoom']);

    Route::get('/medical-record/{patientId}', [MedicalRecordController::class, 'show']);
    Route::post('/medical-record/{patientId}', [MedicalRecordController::class, 'storeOrUpdate']);
    
    Route::get('/patients', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\User::where('role', 'patient')->get()
        ]);
    });

});