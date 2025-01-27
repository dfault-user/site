<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/thumbnail', [Controllers\CdnController::class, 'thumbnail']);
Route::get('/render', [Controllers\CdnController::class, 'render']);
Route::get('/ownership/hasAsset/', [App\Http\Controllers\ClientController::class, 'getAssetOwnership']);
Route::get('/currency/balance', [App\Http\Controllers\ClientController::class, 'getBalanceApiVersion']);
Route::post('/marketplace/purchase', [App\Http\Controllers\CatalogController::class, 'purchaseItemFromGame']);