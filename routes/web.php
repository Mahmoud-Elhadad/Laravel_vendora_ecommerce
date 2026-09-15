<?php

use App\Http\Controllers\AuthEcommController;
use App\Http\Controllers\EcommController;
use App\Http\Middleware\AuthEcommMiddleware;
use Illuminate\Support\Facades\Route;

// Route for home page
Route::get('/', [EcommController::class, 'display_home'])->name('display.home');

// Route for product_details page
Route::get('productDetails/{id}', [EcommController::class, 'product_details'])->name('product.details');

// Route for quick view product data (JSON)
Route::get('productQuickView/{id}', [EcommController::class, 'quick_view'])->name('product.quickview');

// Route for show contact page
Route::get('showMs', [EcommController::class, 'show_message'])->name('show.message');
// Route for store messages
Route::post('storeMs', [EcommController::class, 'store_message'])->name('store.message');

// Route for show register page
Route::get('showRegister', [AuthEcommController::class, 'show_register_page'])->name('ecomm.show.registrPage');
// Route for store ecomm user from register page
Route::post('storeEcommUser', [AuthEcommController::class, 'store_ecommUser'])->name('ecomm.store.user');

// Route for show login page
Route::get('showLogin', [AuthEcommController::class, 'show_login_page'])->name('ecomm.show.loginPage');
// Route for check login userEcomm
Route::post('loginEcommUserCheck', [AuthEcommController::class, 'check_login_ecomm'])->name('ecomm.check.login');


// Routing for Authintication pages
Route::middleware(AuthEcommMiddleware::class)->group(function () {

    // Route for Ecomm User Logout
    Route::get('UserLogout', [AuthEcommController::class, 'ecomm_logout'])->name('ecomm.logout');

    // Route for store in cart
    Route::post('storeCart', [EcommController::class, 'store_cart'])->name('store.cart');
    // Route for show cart page
    Route::get('showCart', [EcommController::class, 'show_cart'])->name('show.cart');
    // Route for remove item from cart
    Route::post('removeItemCart', [EcommController::class, 'remove_item_cart'])->name('remove.item.cart');
    // Route for clear all items from cart
    Route::delete('removeAllItemsCart', [EcommController::class, 'clear_all_cart'])->name('clear.items.cart');

    // Route for show my account page
    Route::get('showMyAccount', [EcommController::class, 'show_account_page'])->name('ecomm.show.accountPage');

    // Route for show and edit my profile page
    Route::get('showAndEditProfilePage', [EcommController::class, 'show_profile_and_edit_page'])->name('ecomm.showAndEdit.profilePage');
    // Route for show and edit my profile page
    Route::post('updateEcommUser', [EcommController::class, 'update_eccomUser'])->name('ecomm.update.ecommUser');

    // Route for change user password
    Route::post('changeEcommUserPassword', [AuthEcommController::class, 'change_ecomm_user_password'])->name('ecomm.change.userPassword');

    // Route for whilist page
    Route::get('whilistPage', [EcommController::class, 'show_whilist_page'])->name('ecomm.whilistPage');
    // Route for store product in whilist
    Route::post('storeWhilist', [EcommController::class, 'store_whilist'])->name('ecomm.store.whilist');
    // Route for store all product's whilist in cart
    Route::post('storeAllWhilistInCart', [EcommController::class, 'store_all_whilist_items_in_cart'])->name('ecomm.store.AllWhilist.cart');
    // Route for clear all whilist items
    Route::delete('clearAllWhilist', [EcommController::class, 'clear_all_whilists'])->name('clear.items.whilist');

});


//Route for show shop page
Route::get("showShopPage" , [EcommController::class , "show_shop_page"])->name("ecomm.shop.page");

//Route for show category page
Route::get("showCategoryPage" , [EcommController::class , "show_category_page"])->name("ecomm.category.page");

// Route for show about page
Route::get('showAboutPage', [EcommController::class, 'show_about_page'])->name('ecomm.about.page');

// Route for show FAQ page
Route::get('showFAQPage', [EcommController::class, 'show_FAQ_page'])->name('ecomm.FAQ.page');

// Route for show privacy page
Route::get('showPrivacyPage', [EcommController::class, 'show_privacy_page'])->name('ecomm.privacy.page');

// Route for show terms of service page
Route::get('showTermsPage', [EcommController::class, 'show_terms_page'])->name('ecomm.terms.page');

// Route for show error 404 page
Route::get('showErrorPage', [EcommController::class, 'show_error_page'])->name('ecomm.error.page');
