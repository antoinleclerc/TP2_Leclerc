<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Middleware\ThrottleRequests;

Route::post('/signup',[Authcontroller::class,'register'])->middleware('throttle:5,1');
Route::get('/signin',[Authcontroller::class,'login'])->middleware('throttle:5,1');
Route::get('/me',[Authcontroller::class,'me'])->middleware('throttle:5,1','auth:sanctum');
Route::post('/refresh',[Authcontroller::class,'refresh'])->middleware('throttle:5,1','auth:sanctum');
Route::post('/signout',[Authcontroller::class,'logout'])->middleware('throttle:5,1','auth:sanctum');


Route::post('/equipments',[EquipmentController::class,'store'])->middleware('throttle:60,1','auth:sanctum');
Route::put('/equipments/{id}',[EquipmentController::class,'update'])->middleware('throttle:60,1','auth:sanctum');
Route::delete('/equipments/{id}',[EquipmentController::class,'destroy'])->middleware('throttle:60,1','auth:sanctum');
Route::post('/reviews',[ReviewController::class,'store'])->middleware('throttle:60,1','auth:sanctum');
Route::get('/rentals',[RentalController::class,'myActiveRentals'])->middleware('throttle:60,1','auth:sanctum');
Route::put('/user/{id}',[UserController::class,'update'])->middleware('throttle:60,1','auth:sanctum');

//Dev routes *** à supprimer avant la de remettre ***
Route::post('/admin',[Authcontroller::class,'registerAdmin']);