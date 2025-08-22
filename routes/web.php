<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExportController;

Route::get('/', function () {
    return view('welcome');
});

// Export routes
Route::get('/export/product-variants/detailed', [ExportController::class, 'exportProductVariantsDetailed'])
    ->name('export.product-variants.detailed');

Route::get('/export/product-variants/simple', [ExportController::class, 'exportProductVariantsSimple'])
    ->name('export.product-variants.simple');
