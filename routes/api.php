<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Models\Admin;
use App\Models\Article;
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
// Mitra Endpoint Start
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
Route::middleware(['auth:sanctum'])->get('logout-mitra', [MitraController::class, 'mitraLogout']);
});
Route::get('mitra/all', [MitraController::class, 'getAllMitra']);
Route::middleware(['auth:sanctum'])->get('mitra/all/admin', [MitraController::class, 'getAllMitraAdmin']);
Route::middleware(['auth:sanctum'])->post('create/mitra', [MitraController::class, 'createMitra']);
Route::middleware(['auth:sanctum'])->post('edit/mitra/{id}', [MitraController::class, 'editMitra']);
Route::middleware(['auth:sanctum'])->delete('delete/mitra/{id}', [MitraController::class, 'deleteMitra']);
Route::get('mitra/all/product/{id}', [MitraController::class, 'getTest']);
// Mitra Endpoint End
// Article Endpoint Start
Route::middleware(['auth:sanctum'])->post('article', [ArticleController::class, 'store']);
Route::get('article/read/{id}', [ArticleController::class, 'getArticle']);
Route::get('article/read-all', [ArticleController::class, 'getAllArticle']);
Route::get('article/home/news-article', [ArticleController::class, 'getNewsArticle']);
Route::middleware(['auth:sanctum'])->get('article/read-all/admin', [ArticleController::class, 'getAllArticleAdmin']);
Route::middleware(['auth:sanctum'])->post('article/edit/{id}', [ArticleController::class, 'update']);
Route::middleware(['auth:sanctum'])->delete('article/delete/{id}', [ArticleController::class, 'destroy']);
// Article Endpoint End
// Product Endpoint Start
Route::middleware(['auth:sanctum'])->post('product/{mitraId}', [ProductController::class, 'createProduct']);
Route::get('product/read/{mitraId}', [ProductController::class, 'getProductsByMitra']);
// Route::middleware(['auth:sanctum'])->get('product/read-mitra/{mitraId}', [ProductController::class, 'getProductsForMitra']);
Route::get('product/homepage', [ProductController::class, 'getProductHomepage']);
Route::get('produk/desc-produk/{productId}', [ProductController::class, 'spesificProduct']);
route::middleware(['auth:sanctum'])->post('product/edit/{productId}', [ProductController::class, 'updateProduct']);
route::middleware(['auth:sanctum'])->delete('product/delete/{productId}', [ProductController::class, 'deleteProduct']);
// Product Endpoint End
// Contact Endpoint Start
Route::post('contact/kirim', [ContactController::class, 'kirimPesan']);
// Contact Endpint End
// Admin Endpoint Start
Route::group(['prefix' => 'admin'], function() {
    Route::post('login', [AdminController::class, 'loginAdmin']);
    Route::middleware(['auth:sanctum'])->get('logout-admin', [AdminController::class, 'adminLogout']);
});
// Admin Endpoint End
// User Endpoint Start
Route::post('register/user', [UserController::class, 'registerUser']);
Route::post('login/user', [UserController::class, 'loginUser']);
Route::post('edit/user/{id}', [UserController::class, 'editUser']);
// User Endpoint End

Route::middleware(['cors'])->group(function () {
    // Rute yang memerlukan middleware CORS
    Route::get('product/read-mitra/{mitraId}', [ProductController::class, 'getProductsForMitra']);
});