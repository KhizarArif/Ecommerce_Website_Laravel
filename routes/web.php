<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
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

Route::get('/', [FrontController::class, 'index'])->name('frontend.home');
// SHOP Routes
Route::controller(ShopController::class)->prefix('shop')->group(function () {
    Route::get('{categorySlug?}/{subcategorySlug?}', 'index')->name('shop.index');
});

Route::group(["prefix" => "admin"], function () {

    Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
    Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');

    Route::group(["middleware" => "is_admin"], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');

        // Category routes 
        Route::controller(CategoryController::class)->prefix('categories')->group(function () {
            Route::get('', 'index')->name('categories.index');
            Route::get('create', 'create')->name('categories.create');
            Route::get('edit/{id}', 'edit')->name('categories.edit');
            Route::post('store', 'store')->name('categories.store');
            Route::delete('delete/{id}', 'destroy')->name('categories.delete');
        });


        // Sub Category routes
        Route::controller(SubCategoryController::class)->prefix('subcategories')->group(function () {
            Route::get('', 'index')->name('subcategories.index');
            Route::get('create', 'create')->name('subcategories.create');
            Route::get('edit/{id}', 'edit')->name('subcategories.edit');
            Route::post('store', 'store')->name('subcategories.store');
            Route::delete('delete/{id}', 'destroy')->name('subcategories.delete');
        });

        // Brands routes
        Route::controller(BrandController::class)->prefix('brands')->group(function () {
            Route::get('', 'index')->name('brands.index');
            Route::get('create', 'create')->name('brands.create');
            Route::get('edit/{id}', 'edit')->name('brands.edit');
            Route::post('store', 'store')->name('brands.store');
            Route::delete('delete/{id}', 'destroy')->name('brands.delete');
        });

        // Products Routes
        Route::controller(ProductController::class)->prefix('products')->group(function () {
            Route::get('', 'index')->name('products.index');
            Route::get('create', 'create')->name('products.create');
            Route::get('edit/{id}', 'edit')->name('products.edit');
            Route::post('store', 'store')->name('products.store');
            Route::delete('delete/{id}', 'destroy')->name('products.delete');
            
            Route::get('getSubCategory', 'GetSubCategory')->name('getSubCategory');

            // Update Product Controller Image
            Route::post('product-image/update', 'updateProductImage')->name('products.updateImage');
            Route::delete('product-image', 'deleteProductImage')->name('products.deleteImage');
        });

        


        // Export Data to Excel
        Route::get('file-export', [CategoryController::class, 'fileExport'])->name('file-export');
        Route::post('file-import', [CategoryController::class, 'fileImport'])->name('file-import');

        // PDF Routes 
        Route::get('view-pdf', [CategoryController::class, 'viewPDF'])->name('view-pdf');
        Route::post('download-pdf', [CategoryController::class, 'downloadPDF'])->name('download-pdf');

        // Image routes
        Route::post('/upload-image', [TempImageController::class, 'create'])->name('image.create');
        Route::delete('/delete-image/{id}', [TempImageController::class, 'deleteImage'])->name('delete.image');


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
    });
});
