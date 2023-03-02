<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use GuzzleHttp\Promise\Create;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/logout',[AuthController::class,'logout']);


//search
Route::get('/post/search/{name}',[PostController::class,'search']);




Route::get('/posts',[PostController::class,'index']);
Route::get('/posts/show/{id}',[PostController::class,'show']);
Route::post('/post/delete/{id}',[PostController::class,'destroy']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profail',[AuthController::class,'userProfile']);
    Route::post('/post/store',[PostController::class,'store']);
    Route::post('/post/update/{id}',[PostController::class,'updata']);


});



