<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PostController;

//post
Route::get('/posts' , [PostController::class , 'index']);
Route::post('/posts' , [PostController::class , 'store']);
Route::get('/posts/{id}' , [PostController::class , 'show']);
Route::put('/posts/{id}' , [PostController::class , 'update']);
Route::delete('/posts/{id}' , [PostController::class , 'destroy']);

//category
Route::get('/categories' , [CategoryController::class , 'index']);
Route::post('/categories' , [CategoryController::class , 'store']);
Route::get('/categories/{id}' , [CategoryController::class , 'show']);
Route::put('/categories/{id}' , [CategoryController::class , 'update']);
Route::delete('/categories/{id}' ,[CategoryController::class , 'destroy']);
