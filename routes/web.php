<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
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

// Registration & Login Routes
Route::group(["prefix" => "account"], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/register', 'register')->name('account.register');
            Route::post('/process-register', 'processRegister')->name('account.processRegister');
            Route::get('/login', 'login')->name('account.login');
            Route::post('/login', 'authenticate')->name('account.authenticate');
        });
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get("/profile", [AuthController::class, 'profile'])->name('account.profile');
        Route::get("/logout", [AuthController::class, 'logout'])->name('account.logout');
    });
});



// SHOP Routes
Route::controller(ShopController::class)->prefix('shop')->group(function () {
    Route::get('{categorySlug?}/{subcategorySlug?}', 'index')->name('front.shop');
});

Route::get('/product/{slug}', [ShopController::class, 'product'])->name('front.product');

// Cart Controller 
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'cart')->name('front.cart');
    Route::post('/add-to-cart', 'addToCart')->name('front.addToCart');
    Route::post('/update-cart', 'updateCart')->name('front.updateCart');
    Route::delete('/delete-cart', 'deleteToCart')->name('front.deleteToCart');
    Route::get('/checkout', 'checkout')->name('front.checkout');
    Route::post('/process-checkout', 'processCheckout')->name('front.processCheckout');
    Route::get('/thankyou/{id}', 'thankyou')->name('front.thankyou');
    Route::post('/get-shipping-amount', 'getShippingAmount')->name('shipping.getShippingAmount');
    Route::post('/apply-discount', 'applyDiscount')->name('shipping.applyDiscount');
    Route::post('/remove-discount', 'removeCoupen')->name('shipping.removeCoupen');
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

            // Related Products Select 2
            Route::get('getProducts', 'getProducts')->name('products.getProducts');
        });


        // Shipping Routes
        Route::controller(ShippingController::class)->prefix('shipping')->group(function () {
            Route::get('/create', 'create')->name('shipping.create');
            Route::post('/store', 'store')->name('shipping.store');
            Route::get('edit/{id}', 'edit')->name('shipping.edit');
            Route::put('update/{id}', 'update')->name('shipping.update');
            Route::delete('delete/{id}', 'destroy')->name('shipping.delete');
        });


        // Discount Codes Routes
        Route::controller(DiscountCodeController::class)->prefix('coupen-codes')->group(function () {
            Route::get('', 'index')->name('coupen.index');
            Route::get('create', 'create')->name('coupen.create');
            Route::get('edit/{id}', 'edit')->name('coupen.edit');
            Route::post('store', 'store')->name('coupen.store');
            Route::delete('delete/{id}', 'destroy')->name('coupen.delete');
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
