<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\ProductController as ProductControllerV1;
use App\Http\Controllers\Api\V1\AuthController as AuthControllerV1;


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


Route::prefix('/v1')->group(function () {

    #Route::apiResource('', TaxnomiesController::class);

    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('products', [ProductControllerV1::class, 'index']);
        Route::post('products', [ProductControllerV1::class, 'store']);
        Route::get('products/{id}', [ProductControllerV1::class, 'show']);
        Route::put('products/{id}', [ProductControllerV1::class, 'update']);
        Route::delete('products/{id}', [ProductControllerV1::class, 'destroy']);
        Route::get('products/{id}/prices', [ProductControllerV1::class, 'getPrices']);
        Route::post('products/{id}/prices', [ProductControllerV1::class, 'storePrice']);
    
    });
    
        // Route::middleware(['auth:sanctum'])->group(function () {

        //     Route::post('/',[ProductControllerV1::class, 'store']);
        //     Route::patch('/{id}',[ProductControllerV1::class, 'update']);
        //     Route::delete('/{id}',[ProductControllerV1::class, 'destroy']);
        // });
   

        Route::prefix('/auth')->group(function () {

            Route::post('login', [AuthControllerV1::class, 'login']);
    
            Route::middleware(['auth:sanctum'])->group(function () {
    
                Route::post('logout', [AuthControllerV1::class, 'logout']);
                Route::post('me', [AuthControllerV1::class, 'me']);
                
                Route::post('register',[
                    AuthControllerV1::class,
                    'register'
                ]);
                    
            });
    
        });

});