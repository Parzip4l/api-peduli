<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produck\ProdukController;
use App\Http\Controllers\General\dashboardController;
use App\Http\Controllers\General\userController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\TestLdapController;
use App\Http\Controllers\LdapLoginController;
use App\Http\Controllers\Master\NotificationController;
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

require __DIR__ . '/auth.php';

Route::get('/login', [LdapLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LdapLoginController::class, 'login'])->name('login.attempt');

Route::get('/', function () {
    return redirect()->route('dashboard.index');
});

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->name('dashboard')->middleware('auth');

Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    // Setting
    Route::resource('menu', App\Http\Controllers\Setting\MenuController::class);
        Route::post('/update-status/{id}', [App\Http\Controllers\Setting\MenuController::class, 'updateStatus'])->name('update.status');
    Route::resource('user', App\Http\Controllers\General\userController::class);
    Route::resource('role', App\Http\Controllers\Setting\RoleController::class);

    // Page
    Route::get('dashboard', [dashboardController::class, 'index'])->name('dashboard.index');


    // Master
    Route::resource('divisi', App\Http\Controllers\Master\DivisionController::class);
    Route::resource('lokasi', App\Http\Controllers\Master\LocationController::class);
    Route::resource('pic', App\Http\Controllers\Master\PicController::class);
        Route::post('/user/pic-update', [App\Http\Controllers\Master\PicController::class, 'updatePIC'])->name('pic.dataupdate');
    Route::resource('hazard', App\Http\Controllers\Master\HazardController::class);
    Route::resource('observation', App\Http\Controllers\Master\ObservationController::class);
    Route::resource('bahaya', App\Http\Controllers\Master\KategoriBahaya::class);


    // Laporan
    Route::resource('laporan', App\Http\Controllers\Report\ReportController::class);

    Route::post('/laporan/{hashid}/review-qshe', [App\Http\Controllers\Report\ReportController::class, 'reviewByQshe'])->name('laporan.review.qshe');
    Route::post('/laporan/{hashid}/review-pic', [App\Http\Controllers\Report\ReportController::class, 'reviewByPic'])->name('laporan.review.pic');
    Route::post('/laporan/{hashid}/submited-pic', [App\Http\Controllers\Report\ReportController::class, 'progresByPic'])->name('laporan.submit.pic');
    Route::post('/laporan/{hashid}/review-submit-pic', [App\Http\Controllers\Report\ReportController::class, 'reviewProgress'])->name('laporan.review-submit.pic');
    Route::post('/delete-laporan/{hashid}', [App\Http\Controllers\Report\ReportController::class, 'destroy'])->name('laporan.destroy');

    Route::get('/get-pic-by-division/{id}', [App\Http\Controllers\Report\ReportController::class, 'getPicByDivision']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/read/{id}', [NotificationController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/clear', [NotificationController::class, 'clearAll']);

});