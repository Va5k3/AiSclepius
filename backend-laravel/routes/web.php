<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// added
use App\Http\Controllers\PredictionController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //added
    //heart-check
    Route::get('/heart-check', [PredictionController::class, 'heartForm'])->name('heart.check');  
    Route::post('/predict-heart', [PredictionController::class, 'predictHeart'])->name('predict.heart');

    //diabetes-check
    Route::get('/diabetes-check', [PredictionController::class, 'diabetesForm'])->name('diabetes.check');
    Route::post('/predict-diabetes',[PredictionController::class,'predictDiabetes'])->name('predict.diabetes');

    //history
    Route::get('/history',[PredictionController::class, 'history'])->name('predict.history');

});

require __DIR__.'/auth.php';
