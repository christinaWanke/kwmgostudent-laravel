<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SlotController;
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

Route::group(['middleware' => ['api', 'auth.jwt']], function (){
    Route::post('courses', [CourseController::class, 'save']);
    Route::put('courses/{cnum}', [CourseController::class,'update']);
    Route::delete('courses/{cnum}', [CourseController::class,'delete']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::post('comments', [CommentController::class, 'save']);
    Route::put('comments/{id}', [CommentController::class, 'update']);
    Route::delete('comments/{id}', [CommentController::class, 'delete']);
});


/*AUTH*/
Route::post('auth/login', [AuthController::class, 'login']);

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/{title}', [CourseController::class, 'findByTitle']);
Route::get('courses/short/{cnum}', [CourseController::class, 'findByCnum']);
Route::get('courses/checkCnum/{cnum}', [CourseController::class, 'checkCNUM']);
Route::get('courses/?{semester}', [CourseController::class, 'findBySemester']);
Route::get('courses/search/{searchTerm}', [CourseController::class, 'findBySearchTerm']);

Route::get('users', [UserController::class, 'getUsers']);
Route::get('users/{id}', [UserController::class, 'getUserById']);
Route::post('users', [UserController::class, 'save']);
Route::put('users/{id}', [UserController::class,'update']);
Route::get('users/isTutor/{id}', [UserController::class, 'isTutor']);


Route::get('comments', [CommentController::class, 'getComments']);
Route::get('comments/{id}', [CommentController::class, 'getCommentById']);


Route::get('slots/booked/{id}', [SlotController::class, 'getBookedSlotsOfTutor']);
Route::get('slots', [SlotController::class, 'getBookedSlotsOfTutor']);
