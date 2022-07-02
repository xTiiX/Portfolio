<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\EducationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('project', ProjectController::class);
Route::get('project/restore/{id}', [ProjectController::class, 'restore']);

Route::resource('education', EducationController::class);
Route::get('education/restore/{id}', [EducationController::class, 'restore']);
