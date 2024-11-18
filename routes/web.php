<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/upload-from-url', [ImageController::class, 'uploadFromUrl']);
