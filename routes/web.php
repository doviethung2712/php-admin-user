<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Admin\Controllers\MasterCategoryController;
use App\Admin\Controllers\UserOperationLogController;

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



Route::middleware('checkauth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Auth::routes(['verify' => true]);
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('homee')->middleware('verified');
    Route::resource('master/category', MasterCategoryController::class);
});
