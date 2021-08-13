<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Tourist\TouristController;
use App\Http\Controllers\Tourist\AuthController;
use App\Http\Controllers\Tourist\ForgetPasswordController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/forgot-password',[ForgetPasswordController::class,'forgotPassword']);
Route::get('/login',function()
{
    return response()->json([
        'success'=>false,
        'message'=>'Unauthorized'
    ],401);
})->name('login');
Route::middleware('auth:api')->group(function()
{
    Route::post('/logout',[AuthController::class,'logout']);
    Route::resource('/tourist',TouristController::class);
});


