<?php

use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ContactController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// contact routes 
Route::get("contacts", [ContactController::class , "index"]); 
Route::post("contacts" , [ContactController::class, "add"]); 
Route::get("contacts/{id}", [ContactController::class, "show"]); 
Route::put("contacts/{id}", [ContactController::class, "update"]); 


// company routes 
Route::get("companies", [CompanyController::class , "index"]) ; 