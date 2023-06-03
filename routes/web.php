<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
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

// Route::get('/', function () {
//     return view('productPage');
// });
Route::get('/', [ProductController::class, 'Index'])->name('index');

Auth::routes(); 

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/product/page', [ProductController::class, 'ProductPage'])->name('product.page');
Route::get('/cart', [CheckOutController::class, 'cart'])->name('cart.page');
Route::get('/checkout/page', [CheckOutController::class, 'CheckOutPage'])->name('checkout.page');


// cart Route start
Route::post('/cart/data/store/{id}', [CheckOutController::class, 'AddToCart']);
Route::get('/user/get-cart-product', [CheckOutController::class, 'GetCartProduct']);
Route::get('/user/get-cart-total-price', [CheckOutController::class, 'GetCartTotalPrice']);
Route::get('/cart-increment/{rowId}', [CheckOutController::class, 'CartIncrement']);
Route::get('/cart-decrement/{rowId}', [CheckOutController::class, 'CartDecrement']);
Route::get('/user/cart-remove/{rowId}', [CheckOutController::class, 'RemoveCartProduct']);
// cart Route end

// coupon Route start
Route::post('/coupon-apply', [CheckOutController::class, 'CouponApply']);
Route::get('/coupon-calculation', [CheckOutController::class, 'CouponCalculation']);
Route::get('/coupon-remove', [CheckOutController::class, 'CouponRemove']);
Route::get('/user/get-checkout-total-price', [CheckOutController::class, 'GetCheckoutTotalPrice']);
Route::get('/coupon-remove', [CheckOutController::class, 'CouponRemove']);
// coupon Route end

// checkout Route start
Route::post('/user/information/store', [CheckOutController::class, 'UserInformation']);
Route::get('/shipping', [CheckOutController::class, 'Shipping']);
Route::get('/contact-information-info-show', [CheckOutController::class, 'UserInfoShow']);
Route::get('/user/information/edit', [CheckOutController::class, 'UserInfoEdit']);
Route::post('/user/information/update/store', [CheckOutController::class, 'UserInfoUpdate']);
// checkout Route end

// shipping Route star
Route::post('/user/shipping/store', [CheckOutController::class, 'ShippingStore']);

Route::get('/summary', [CheckOutController::class, 'Summary']);
Route::get('/shipping-info-show', [CheckOutController::class, 'ShippingInfoShow']);
Route::get('/shipping/information/edit', [CheckOutController::class, 'ShippingInfoEdit']);
Route::post('/user/shipping/update', [CheckOutController::class, 'ShippingInfoUpdate']);

Route::post('/oder/confirm', [CheckOutController::class, 'UserOderConfirm'])->name('oder.confirm');


Route::post('/new/user/password/show', [CheckOutController::class, 'PasswordShow']);
Route::post('/new/user/password/hide', [CheckOutController::class, 'PasswordHide']);

// User Route 
Route::get('/profile', [ProfileController::class, 'Profile'])->name('user.profile');
Route::get('/change/profile', [ProfileController::class, 'ChangeProfile'])->name('change.profile');
Route::post('/update/profile', [ProfileController::class, 'UpdateProfile'])->name('update.profile');

