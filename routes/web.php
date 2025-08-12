<?php

use App\Http\Controllers\Admin\CategorySurveyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorySurveyConntroller;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HasilSurveiController;
use App\Http\Controllers\PertanyaanSurveiController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\SurveyController;
use App\Models\Survey;
use Illuminate\Support\Facades\Route;



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'ActionLogin'])->name('login.action');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/survey/{slug}', [SurveyController::class, 'show'])->name('survey.show');
Route::post('/survey/{slug}', [SurveyController::class, 'submit'])->name('survey.submit');

Route::middleware(['auth'])->group(function () {
    Route::get('/',[DashboardController::class, 'index']);

    Route::resource('/survei', CategorySurveyController::class);

    // Route pertanyaan ini berada pada menu survei
    Route::resource('/pertanyaan', PertanyaanSurveiController::class);

    // Route hasil survei permacam macamnya
    Route::get('/hasil-survei', [HasilSurveiController::class, 'index']);
    Route::get('/hasil-survei/{slug}', [HasilSurveiController::class, 'show'])->name('hasil-survei.show');
    Route::get('/hasil-survei/{slug}/responses/{id}', [HasilSurveiController::class, 'responses'])->name('hasil-survei.response.show');

});
