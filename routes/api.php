<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\DocumentController;

Route::post('templates', [TemplateController::class, 'store']);
Route::post('templates/{id}/generate', [DocumentController::class, 'generateDocument']);

?>