<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;

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

// 2fa middleware
Route::middleware(['2fa'])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/accounts', [AccountController::class, 'index'])->name('accounts');
    Route::post('/accounts/create', [AccountController::class, 'create'])->name('create_account');
    Route::post('/accounts/remove', [AccountController::class, 'remove'])->name('remove_account');

    Route::get('/history', [HistoryController::class, 'index'])->name('history');
    Route::get('/incoming-payment', [PaymentController::class, 'indexIncoming'])->name('incoming-payment');
    Route::get('/outcoming-payment', [PaymentController::class, 'indexOutcoming'])->name('outcoming-payment');

    Route::post('/2fa', function () {
        return redirect(route('home'));
    })->name('2fa');

});

Route::get('/complete-registration', [RegisterController::class, 'completeRegistration'])->name('complete.registration');
