<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

//home route
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('checkPlan');
Route::post('report-user', [App\Http\Controllers\HomeController::class, 'reportUser']);
Route::get('check-user', [App\Http\Controllers\HomeController::class, 'checkUser']);
Route::get('get-details', [App\Http\Controllers\HomeController::class, 'getDetails']);

//check if auth mode is enabled
Route::middleware('checkAuthMode')->group(function () {
    Route::get('pricing', [App\Http\Controllers\PaymentController::class, 'index'])->name('pricing');
    Route::get('payment', [App\Http\Controllers\PaymentController::class, 'payment'])->name('payment');
    Route::post('handlePayment', [App\Http\Controllers\PaymentController::class, 'handlePayment'])->name('handlePayment');
    Route::get('profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');
});

//admin routes
Route::middleware('checkAdmin')->group(function () {
    Route::get('admin', [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    Route::get('income', [App\Http\Controllers\AdminController::class, 'income'])->name('income');
    Route::get('update', [App\Http\Controllers\AdminController::class, 'update'])->name('update');
    Route::get('check-for-update', [App\Http\Controllers\AdminController::class, 'checkForUpdate']);
    Route::get('download-update', [App\Http\Controllers\AdminController::class, 'downloadUpdate']);
    Route::get('license', [App\Http\Controllers\AdminController::class, 'license'])->name('license');
    Route::get('verify-license', [App\Http\Controllers\AdminController::class, 'verifyLicense']);
    Route::get('uninstall-license', [App\Http\Controllers\AdminController::class, 'uninstallLicense']);
    Route::get('signaling', [App\Http\Controllers\AdminController::class, 'signaling'])->name('signaling');
    Route::get('check-signaling', [App\Http\Controllers\AdminController::class, 'checkSignaling']);

    //user routes
    Route::get('users', [App\Http\Controllers\UserController::class, 'index'])->name('users');
    Route::post('update-user-status', [App\Http\Controllers\UserController::class, 'updateUserStatus']);

    //reportedUsers routes
    Route::get('reported-users', [App\Http\Controllers\ReportedUserController::class, 'index'])->name('reportedUsers');
    Route::post('ignore-user', [App\Http\Controllers\ReportedUserController::class, 'ignoreUser']);
    Route::post('ban-user', [App\Http\Controllers\ReportedUserController::class, 'banUser']);
    Route::get('banned-users', [App\Http\Controllers\ReportedUserController::class, 'bannedUsers'])->name('bannedUsers');
    Route::post('unban-user', [App\Http\Controllers\ReportedUserController::class, 'unbanUser']);
    Route::post('bulk-ignore-users', [App\Http\Controllers\ReportedUserController::class, 'bulkIgnoreUser']);
    Route::post('bulk-ban-users', [App\Http\Controllers\ReportedUserController::class, 'bulkBanUser']);
    Route::post('bulk-unban-users', [App\Http\Controllers\ReportedUserController::class, 'bulkUnbanUser']);

    //global config routes
    Route::get('global-config', [App\Http\Controllers\GlobalConfigController::class, 'index'])->name('global-config');
    Route::get('global-config/edit/{id}', [App\Http\Controllers\GlobalConfigController::class, 'edit']);
    Route::post('update-global-config', [App\Http\Controllers\GlobalConfigController::class, 'update']);

    //pages routes
    Route::get('pages', [App\Http\Controllers\PageController::class, 'index'])->name('pages');
    Route::get('pages/edit/{id}', [App\Http\Controllers\PageController::class, 'edit']);
    Route::post('update-page', [App\Http\Controllers\PageController::class, 'update']);

    //fatures routes
    Route::get('features', [App\Http\Controllers\FeaturesController::class, 'index'])->name('features');
    Route::post('update-feature-status', [App\Http\Controllers\FeaturesController::class, 'updateFeatureStatus']);
    Route::post('update-feature-paid', [App\Http\Controllers\FeaturesController::class, 'updateFeaturePaid']);

    //languages routes
    Route::get('languages', [App\Http\Controllers\LanguagesController::class, 'index'])->name('languages');
    Route::get('languages/add', [App\Http\Controllers\LanguagesController::class, 'create']);
    Route::post('create-language', [App\Http\Controllers\LanguagesController::class, 'createLanguage'])->name('createLanguage');
    Route::get('languages/edit/{id}', [App\Http\Controllers\LanguagesController::class, 'edit']);
    Route::post('update-language', [App\Http\Controllers\LanguagesController::class, 'updateLanguage']);
    Route::post('languages/delete', [App\Http\Controllers\LanguagesController::class, 'deleteLanguage']);
    Route::get('languages/download-english', [App\Http\Controllers\LanguagesController::class, 'downloadEnglish']);
    Route::get('languages/download-file/{code}', [App\Http\Controllers\LanguagesController::class, 'downloadFile']);
});

//change password
Route::get('change-password', [App\Http\Controllers\ChangePasswordController::class, 'index'])->name('changePassword');
Route::post('update-password', [App\Http\Controllers\ChangePasswordController::class, 'changePassword']);

//extra routes
Route::get('privacy-policy', function () {
    return view('privacy-policy', [
        'page' => __('Privacy Policy'),
    ]);
})->name('privacyPolicy');

Route::get('terms-and-conditions', function () {
    return view('terms-and-conditions', [
        'page' => __('Terms & Conditions'),
    ]);
})->name('termsAndConditions');

Route::get('languages/{locale}', [App\Http\Controllers\HomeController::class, 'setLocale'])->name('language');
