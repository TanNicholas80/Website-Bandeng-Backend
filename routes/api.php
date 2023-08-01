<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VerificationController;
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

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'v1'], function() {
    Route::post('register', [MitraController::class, 'register']);
    Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::put('mitra/edit/{id}', [MitraController::class, 'editProfile']);
    Route::get('mitra/read/{id}', [MitraController::class, 'getMitra']);
});
Route::group(['prefix' => 'v2'], function() {
Route::post('login', [MitraController::class, 'login']);
Route::post('login/forgot-password', [MitraController::class, 'forgotPassword']);
});

Route::post('article', [ArticleController::class, 'store']);
Route::get('article/read/{id}', [ArticleController::class, 'getArticle']);
Route::put('article/edit/{id}', [ArticleController::class, 'update']);
Route::delete('article/delete/{id}', [ArticleController::class, 'destroy']);

Route::post('product/{mitraId}', [ProductController::class, 'createProduct']);
Route::get('product/read/{mitraId}', [ProductController::class, 'getProductsByMitra']);
route::put('product/edit/{mitraId}/{productId}', [ProductController::class, 'updateProduct']);
route::delete('product/delete/{mitraId}/{productId}', [ProductController::class, 'deleteProduct']);