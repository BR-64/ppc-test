<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\pCollectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\pProductController;
use App\Http\Controllers\pProductController_test;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\testapi;
use App\Http\Livewire\ShopScroll;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\kCheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::post('/json', [testapi::class, 'getJSON'])->name('getjson');

Route::get('/lv', ShopScroll::class);

Route::get('/infi', [pProductController::class, 'infinit'])->name('product.infini');

Route::get('/bship', [ProfileController::class, 'billshipView'])->name('billship');

Route::get('/profile/company', [ProfileController::class, 'companyview'])->name('profile.company');

Route::get('/checkoutinfo', function(){
    return view('checkout.index');
});

Route::middleware(['guestOrVerified'])->group(function () {
    Route::get('/ppt', [pProductController::class, 'test'])->name('test');

    Route::get('/shop', [pProductController::class, 'index'])->name('shop');
    Route::get('/shop/f', [pProductController::class, 'qfilter'])->name('shopf');
    Route::get('/shop/f2', [pProductController::class, 'qfilter2'])->name('shopf2');
    Route::get('/shop/{cat?}', [pProductController::class, 'catFilter'])->name('shop.cat');
    Route::get('/', [pProductController::class, 'test'])->name('test');
    Route::get('/product/{product:item_code}', [pProductController::class, 'view'])->name('product.view');

    Route::get('/collection', [pCollectionController::class, 'index'])->name('product.collection');
    Route::get('/collection/{col?}', [pCollectionController::class, 'view'])->name('product.collection.view');
    Route::get('/prem', [pCollectionController::class, 'prem'])->name('homeprem');


    

    Route::prefix('/cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{product:item_code}', [CartController::class, 'add'])->name('add');
        Route::post('/remove/{product:item_code}', [CartController::class, 'remove'])->name('remove');
        Route::post('/update-quantity/{product:item_code}', [CartController::class, 'updateQuantity'])->name('update-quantity');

        // Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add');
        // Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove');
        // Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity');
        
    });
});

Route::middleware(['auth', 'verified'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update');
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update');
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout');
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view');





//  Quotation 
Route::post('/quotation', [CheckoutController::class, 'quotation'])->name('cart.quotation');

});

Route::post('/credit', [CheckoutController::class, 'credit'])->name('checkout.credit');

Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

Route::post('/credit', [kCheckoutController::class, 'webhook']);


require __DIR__ . '/auth.php';
