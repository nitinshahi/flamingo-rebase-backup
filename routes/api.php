<?php

use App\Http\Controllers\API\V1\Admin\Auth\LoginController;
use App\Http\Controllers\API\V1\Admin\Blog\AuthorController;
use App\Http\Controllers\API\V1\Admin\Blog\BlogCategoryController;
use App\Http\Controllers\API\V1\Admin\Blog\BlogController;
use App\Http\Controllers\API\V1\Admin\Setting\SettingController;
use App\Http\Controllers\API\V1\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('test', function () {
    return 1;
});
Route::prefix('v1')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', LoginController::class);
        });
        Route::prefix('user')->middleware('auth:api')->controller(UserController::class)->group(function () {
            Route::get('/', 'getLoggedInUser');
            Route::get('list', 'index');
            Route::post('create', 'store');
            Route::put('edit/{id}', 'update');
            Route::prefix('bulk')->group(function () {
                Route::post('delete', 'bulkDelete');
                Route::post('restore', 'bulkRestore');
                Route::post('perma-delete', 'bulkPermaDelete');
            });
        });
        Route::prefix('setting')->controller(SettingController::class)->group(function () {
            Route::post('add-settings', 'store');
            Route::get('all-settings', 'index');
            Route::get('show/{id}', 'show');
            Route::put('edit/{id}', 'editsettings');
            Route::post('update', 'update');
            Route::prefix('bulk')->group(function () {
                Route::post('delete', 'bulkDelete');
                Route::post('restore', 'bulkRestore');
                Route::post('perma-delete', 'bulkPermaDelete');
            });
        });
        Route::prefix('author')->controller(AuthorController::class)->group(function () {
            Route::post('add-author', 'store');
            Route::get('all-author', 'index');
            Route::get('show/{id}', 'show');
            Route::post('edit/{id}', 'update');
            Route::post('test', 'test');
        });
        Route::prefix('blogcategory')->controller(BlogCategoryController::class)->group(function () {
            Route::post('add-blogcategory', 'store');
            Route::get('all', 'index');
            Route::get('show/{id}', 'show');
            Route::post('edit/{id}', 'update');
        });
        Route::prefix('blog')->controller(BlogController::class)->group(function () {
            Route::post('add', 'store');
            Route::get('all', 'index');
            Route::get('show/{id}', 'show');
            Route::post('edit/{id}', 'update');
        });
    });
});
