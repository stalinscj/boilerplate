<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController;

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
