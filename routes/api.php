<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use Illuminate\Routing\Middleware\ThrottleRequests;

Route::post('/signup',[Authcontroller::class,'register'])->middleware('throttle:5,1');
Route::get('/signin',[Authcontroller::class,'login'])->middleware('throttle:5,1');
Route::get('/me',[Authcontroller::class,'me'])->middleware('throttle:5,1','auth:sanctum');
Route::post('/refresh',[Authcontroller::class,'refresh'])->middleware('throttle:5,1','auth:sanctum');
Route::post('/signout',[Authcontroller::class,'logout'])->middleware('throttle:5,1','auth:sanctum');

