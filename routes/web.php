<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});




Route::group(["prefix" => "admin"], function () {
 
        Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate'); 

    // Route::group(["middleware" => "is_admin"], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category routes
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::post('/categories/delete', [CategoryController::class, 'destroy'])->name('categories.delete');
        
        // Export Data to Excel
        Route::get('file-export', [CategoryController::class, 'fileExport'])->name('file-export');
        Route::post('file-import', [CategoryController::class, 'fileImport'])->name('file-import');

        // PDF Routes 
        Route::get('view-pdf', [CategoryController::class, 'viewPDF'])->name('view-pdf');
        Route::post('download-pdf', [CategoryController::class, 'downloadPDF'])->name('download-pdf');

        // Image routes
        Route::post('/upload-image', [TempImageController::class, 'create'])->name('image.create');

        Route::get('/getSlug', function (Request $request) {
            $slug = "";                                     
            if (!empty($request->title)) {
                $slug = Str::slug($request->title);
            }

            return response()->json([
                "status" => true,
                "slug" => $slug,
            ]);
        })->name('getSlug');
    // });
});
