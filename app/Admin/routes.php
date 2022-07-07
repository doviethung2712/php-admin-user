<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers\HomeController;
use App\Admin\Controllers\MasterCategoryController;
use App\Admin\Controllers\UserOperationLogController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {
    $router->get('/', [HomeController::class, 'index'])->name('home');
    $router->resource('master/category', MasterCategoryController::class);
    $router->resource('user/log', UserOperationLogController::class);
});
