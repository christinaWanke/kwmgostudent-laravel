<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{title}', [CourseController::class, 'findByTitle']);
Route::get('courses/?{semester}', [CourseController::class, 'findBySemester']);
Route::get('courses/search/{searchTerm}', [CourseController::class, 'findBySearchTerm']);
Route::post('courses', [CourseController::class, 'save']);
Route::put('courses/{id}', [CourseController::class,'update']);
Route::delete('courses/{title}', [CourseController::class,'delete']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUserById']);
Route::post('users', [UserController::class, 'save']);
Route::put('users/{id}', [UserController::class,'update']);

