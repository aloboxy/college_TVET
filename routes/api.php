<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PosController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// POS Integration Routes
Route::group(['prefix' => 'pos', 'namespace' => 'Api', 'middleware' => 'pos_auth'], function () {
    Route::post('verify-student', [PosController::class, 'verifyStudent']);
    Route::post('process-payment', [PosController::class, 'processPayment']);
    Route::get('student-balance/{student_id}', [PosController::class, 'getBalance']);
});
