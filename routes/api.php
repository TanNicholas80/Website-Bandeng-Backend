<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::middleware(['auth:sanctum'])->get('/mitra', [MitraController::class, 'show']);
Route::group(['prefix' => 'v1'], function() {
    Route::post('register', [MitraController::class, 'register']);
    Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');
    Route::middleware(['auth:sanctum'])->post('mitra/edit/{id}', [MitraController::class, 'editProfile']);
    Route::middleware(['auth:sanctum'])->get('mitra/read/{id}', [MitraController::class, 'getMitra']);
    Route::middleware(['auth:sanctum'])->post('mitra/edit-foto/{id}', [MitraController::class, 'editFotoMitra']);
});
Route::group(['prefix' => 'v2'], function() {
Route::post('login', [MitraController::class, 'login']);
Route::post('login/forgot-password', [MitraController::class, 'reqPasswordBaru']);
Route::post('login/reset-password', [MitraController::class, 'forgotPassword']);
});

Route::middleware(['auth:sanctum'])->post('article', [ArticleController::class, 'store']);
Route::get('article/read/{id}', [ArticleController::class, 'getArticle']);
Route::get('article/read-all', [ArticleController::class, 'getAllArticle']);
Route::middleware(['auth:sanctum'])->post('article/edit/{id}', [ArticleController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('article/delete/{id}', [ArticleController::class, 'destroy']);

Route::middleware(['auth:sanctum'])->post('product/{mitraId}', [ProductController::class, 'createProduct']);
Route::get('product/read/{mitraId}', [ProductController::class, 'getProductsByMitra']);
route::middleware(['auth:sanctum'])->post('product/edit/{mitraId}/{productId}', [ProductController::class, 'updateProduct']);
route::middleware(['auth:sanctum'])->delete('product/delete/{mitraId}/{productId}', [ProductController::class, 'deleteProduct']);

Route::post('contact/kirim', [ContactController::class, 'kirimPesan']);