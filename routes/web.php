<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolePermissionController;

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

/***********************************************************************************************************************
 *                                              Rutas Privadas                                                         *
 ***********************************************************************************************************************/
Route::group(['middleware' => ['auth', 'access'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('permissions', PermissionController::class);
    Route::delete('permissions', [PermissionController::class, 'clean'])->name('permissions.clean');

    Route::resource('roles', RoleController::class);

    Route::resource('roles.permissions', RolePermissionController::class)->only('create', 'store');

    Route::resource('users', UserController::class);
    Route::post('users/{user}', [UserController::class, 'restore'])->name('users.restore');
});


/***********************************************************************************************************************
 *                                              Rutas Protegidas                                                       *
 ***********************************************************************************************************************/
Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});


/***********************************************************************************************************************
 *                                              Rutas Públicas                                                         *
 ***********************************************************************************************************************/
Route::get('/', fn() => view('welcome'));

Auth::routes();
