<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\BrandController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DiscountCodeController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\PageController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SettingController;
use App\Http\Controllers\admin\ShippingController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ExhibitionController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ShopController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

use function App\Helpers\orderEmail;

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



Route::get('login/google', [LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('callback/login', [LoginController::class, 'redirectToGoogleCallback'])->name('login.google.callback');

Route::get('/', [FrontController::class, 'index'])->name('frontend.home');
Route::post('/add_wishlist', [FrontController::class, 'addToWishlist'])->name('frontend.addToWishlist');

Route::get('/user_forgot_password', [AuthController::class, 'userForgotPassword'])->name('account.userForgotPassword');
Route::get('/user_reset_password/{token}', [AuthController::class, 'userResetPassword'])->name('account.userResetPassword'); 
Route::post('/process_forgot_password', [AuthController::class, 'processForgotPassword'])->name('account.processForgotPassword');
Route::post('/process_update_password', [AuthController::class, 'processUpdatePassword'])->name('account.processUpdatePassword');


// Registration & Login Routes
Route::group(["prefix" => "account"], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::controller(AuthController::class)->group(function () {
            Route::get('/register', 'register')->name('account.register');
            Route::post('/process_register', 'processRegister')->name('account.processRegister');
            Route::get('/login', 'login')->name('account.login');
            Route::post('/login', 'authenticate')->name('account.authenticate');
        });
    });

    Route::group(['middleware' => 'auth'], function () {
        Route::get("/profile", [AuthController::class, 'profile'])->name('account.profile');
        Route::get("/update_profile", [AuthController::class, 'updateProfile'])->name('account.updateProfile');
        Route::get("/my_orders", [AuthController::class, 'orders'])->name('account.orders');
        Route::get("/my_wishlists", [AuthController::class, 'wishlists'])->name('account.wishlists');
        Route::get("/order_detail/{id}", [AuthController::class, 'orderDetail'])->name('account.orderDetail');
        Route::post("/remove_from_wishlist", [AuthController::class, 'removeFromWishlist'])->name('account.removeFromWishlist');
        Route::get("/show_change_password", [AuthController::class, 'showChangePassword'])->name('account.showChangePassword');
        Route::post("/change_password", [AuthController::class, 'changePassword'])->name('account.changePassword');
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
    Route::post('/add_to_cart', 'addToCart')->name('front.addToCart');
    Route::post('/update_cart', 'updateCart')->name('front.updateCart');
    Route::delete('/delete_cart', 'deleteToCart')->name('front.deleteToCart');
    Route::get('/checkout', 'checkout')->name('front.checkout');
    Route::post('/process_checkout', 'processCheckout')->name('front.processCheckout');
    Route::get('/thankyou/{id}', 'thankyou')->name('front.thankyou');
    Route::post('/get_shipping_amount', 'getShippingAmount')->name('shipping.getShippingAmount');
    Route::post('/apply_discount', 'applyDiscount')->name('shipping.applyDiscount');
    Route::post('/remove_discount', 'removeCoupen')->name('shipping.removeCoupen');
});





//======================= All Admin Routes =========================================================

Route::group(["prefix" => "admin"], function () {

    Route::get('/login', [AdminController::class, 'index'])->name('admin.login');
    Route::post('/authenticate', [AdminController::class, 'authenticate'])->name('admin.authenticate');
    Route::get('/forgot_admin_password', [AdminController::class, 'forgotAdminPassword'])->name('admin.forgotAdminPassword');
    Route::get('/update_admin_password/{token}', [AdminController::class, 'updateAdminPassword'])->name('admin.updateAdminPassword');
    Route::post('/process_admin_password', [AdminController::class, 'processAdminPassword'])->name('admin.processAdminPassword');
    Route::post('/process_update_admin_password', [AdminController::class, 'processUpdateAdminPassword'])->name('admin.processUpdateAdminPassword');
    

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

        // Exhibitions 
        Route::controller(ExhibitionController::class)->prefix('exhibitions')->group(function () {
            Route::get('', 'index')->name('exhibitions.index');
            Route::get('create', 'create')->name('exhibitions.create');
            Route::get('edit/{id}', 'edit')->name('exhibitions.edit');
            Route::post('store', 'store')->name('exhibitions.store');
            Route::delete('delete/{id}', 'destroy')->name('exhibitions.delete');

            // Update Exhibition Controller Image
            Route::post('exhibition_image/update', 'updateExhibitionImage')->name('exhibitions.updateExhibitionImage');
            Route::delete('exhibition_image', 'deleteExhibitionImage')->name('exhibitions.deleteExhibitionImage');
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
            Route::post('product_image/update', 'updateProductImage')->name('products.updateImage');
            Route::delete('product_image', 'deleteProductImage')->name('products.deleteImage');

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


        // Order Details 
        Route::controller(OrderController::class)->prefix('orders')->group(function () {
            Route::get('', 'index')->name('orders.index');
            Route::get('edit/{id}', 'edit')->name('orders.edit');
            Route::post('change_status/{id}', 'changeOrderStatus')->name('orders.changeOrderStatus');
            Route::post('send_email_invoice/{id}', 'sendEmailInvoice')->name('orders.sendEmailInvoice');
        });


        // Admin Users Route Details 
        Route::controller(UserController::class)->prefix('users')->group(function () {
            Route::get('/', 'index')->name('users.index');
            Route::get('create', 'create')->name('users.create');
            Route::get('/edit/{id}', 'edit')->name('users.edit');
            Route::post('/store', 'store')->name('users.store');
            Route::delete('/delete/{id}', 'destroy')->name('users.delete');
        });


        // Admin Pages Route Details 
        Route::controller(PageController::class)->prefix('pages')->group(function () {
            Route::get('/', 'index')->name('pages.index');
            Route::get('create', 'create')->name('pages.create');
            Route::get('/edit/{id}', 'edit')->name('pages.edit');
            Route::post('/store', 'store')->name('pages.store');
            Route::delete('/delete/{id}', 'destroy')->name('pages.delete');
        });


        Route::controller(SettingController::class)->prefix('setting')->group(function () {
            Route::get('change_admin_password', 'changeAdminPassword')->name('setting.changeAdminPassword');
            Route::post('update_admin_password', 'updateAdminPassword')->name('setting.updateAdminPassword');
        });


        // Export Data to Excel
        Route::get('file_export', [CategoryController::class, 'fileExport'])->name('file-export');
        Route::post('file_import', [CategoryController::class, 'fileImport'])->name('file-import');

        // PDF Routes 
        Route::get('view_pdf', [CategoryController::class, 'viewPDF'])->name('view-pdf');
        Route::post('download_pdf', [CategoryController::class, 'downloadPDF'])->name('download-pdf');

        // Image routes
        Route::post('/upload_image', [TempImageController::class, 'create'])->name('image.create');
        Route::delete('/delete_image/{id}', [TempImageController::class, 'deleteImage'])->name('delete.image');


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
