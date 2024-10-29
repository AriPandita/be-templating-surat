<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\UsersController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::post('/register', [UsersController::class, 'register']);
Route::post('/login', [UsersController::class, 'login']);

Route::prefix('templates')->group(function () {
    Route::get('/', [TemplateController::class, 'index']);
    Route::get('/pending', [TemplateController::class, 'getPendingTemplates']); // Get all pending templates(Need to be approved)
    Route::post('/', [TemplateController::class, 'storeTemplate']);
    Route::post('/{id}/approve', [TemplateController::class, 'approveTemplate']);
    Route::delete('/{id}', [TemplateController::class, 'deleteTemplate']);
    Route::get('/soft-deleted', [TemplateController::class, 'getSoftDeletedTemplates']);
    Route::post('/{id}/restore', [TemplateController::class, 'restoreTemplate']);
});


