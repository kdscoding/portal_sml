<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\InboundCheckController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/inbound-check', [InboundCheckController::class, 'index'])->name('inbound.check');
Route::get('/inbound-check/versions', [InboundCheckController::class, 'versions']);
Route::post('/inbound-check/scan', [InboundCheckController::class, 'scan']);
Route::get('/inbound-check/progress', [InboundCheckController::class, 'progress']);

Route::get('/import', [ImportController::class, 'index'])->name('import.index');
Route::post('/import', [ImportController::class, 'store']);
Route::post('/import/preview', [ImportController::class, 'preview']);
Route::get('/import/versions', [ImportController::class, 'versions']);
Route::get('/import/version/{version}', [ImportController::class, 'showVersion'])->name('import.version.show');
Route::delete('/import/version/{version}', [ImportController::class, 'destroyVersion'])->name('import.version.destroy');

Route::get('/data', [ImportController::class, 'data'])->name('data');
Route::get('/data/version/{version}', [ImportController::class, 'showVersion'])->name('data.version.show');
