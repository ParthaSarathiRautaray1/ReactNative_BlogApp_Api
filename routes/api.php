<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;
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

//comments
Route::get('/comments' , [CommentController::class , 'index']);
Route::post('/comments' , [CommentController::class , 'store']);
Route::get('/comments/{id}' , [CommentController::class , 'show']);
Route::put('/comments/{id}' , [CommentController::class , 'update']);
Route::delete('/comments/{id}' , [CommentController::class , 'destroy']);

//Like
Route::get('/likes' , [LikeController::class , 'index']);
Route::post('/likes' , [LikeController::class , 'store']);
Route::get('/likes/{id}' , [LikeController::class , 'show']);
Route::delete('/likes/{id}' , [LikeController::class , 'destroy']);
