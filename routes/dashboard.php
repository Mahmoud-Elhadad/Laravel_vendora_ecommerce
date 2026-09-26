<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthDashController;
use App\Http\Controllers\CatController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\AuthAdminMiddleware;
use App\Models\Cat;
use App\Models\EcommUser;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::middleware(AuthAdminMiddleware::class)->group(function () {

    Route::get('index', function () {
        $num_all = Product::count();

        $eight_products = Product::latest()->with('cat', 'image')->take(8)->get();

        $all_clients = EcommUser::count();

        $all_cats = Cat::count();

        return view('Dashboard.pages.index', compact('num_all', 'eight_products', 'all_clients', 'all_cats'));
    })->name('vendora.index');

    // Route for adminController
    Route::resource('admin', AdminController::class);
    Route::get('notify', [AdminController::class, 'notification'])->name('admin.notify');
    Route::post('notify/{id}/read', [AdminController::class, 'read_notify'])->name('admin.notify.read');

    // Route for catController
    Route::resource('cat', CatController::class);

    // Route for prdouctController
    Route::resource('product', ProductController::class);

    // Route for my profile page
    Route::get('myProfile', [AuthDashController::class, 'view_profile'])->name('view.myProfile');

    // Route for change admin password
    Route::put('changePassword', [AuthDashController::class, 'change_password'])->name('change.password');

    // Route for change admin image
    Route::post('changeImage', [AuthDashController::class, 'change_image'])->name('change.image');

    // Route for show messages page in dashboard
    Route::get('showDashMs', [MessageController::class, 'show_dash_message'])->name('dash.show.message');
    // Route for update view messages
    Route::post('updateViewMs', [MessageController::class, 'update_view_message'])->name('dash.update.viewMessage');
    // Route for delete message
    Route::delete('deleteMs/{id}', [MessageController::class, 'delete_message'])->name('delete.message');

    // Route for customer controller
    Route::resource('customer', CustomerController::class);

});

// Route for Authintication (login - logout - check)
Route::get('DashLogin', [AuthDashController::class, 'login_form_dash'])->name('dash.loginForm');
Route::post('DashCheck', [AuthDashController::class, 'check_dash'])->name('dash.check');
Route::get('DashLogout', [AuthDashController::class, 'logout_dash'])->name('dash.logout');
