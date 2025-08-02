<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PdfController;

Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
Route::get('/invoices/{id}/download', [InvoiceController::class, 'download']);
Route::post('/generate-pdf', [PdfController::class, 'generate']);
