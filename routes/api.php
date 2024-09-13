<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthenticationController\AuthenticationController;
use App\Http\Controllers\NoteController\NoteController;



Route::post("/login", [AuthenticationController::class, 'issueAuthToken']);
Route::post("/logout", [AuthenticationController::class, 'issueLogoutToken'])->middleware('auth:sanctum');
Route::post("/register", [AuthenticationController::class, 'issueRegister']);
Route::get('/users/{id}', [AuthenticationController::class, 'show'])->middleware('auth:sanctum');


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/posts', [NoteController::class, 'index']);
    Route::post('/posts', [NoteController::class, 'store']);
    Route::get('/posts/{id}', [NoteController::class, 'show']);
    Route::patch('/posts/{id}', [NoteController::class, 'update']);
    Route::delete('/posts/{id}', [NoteController::class, 'destroy']);
});