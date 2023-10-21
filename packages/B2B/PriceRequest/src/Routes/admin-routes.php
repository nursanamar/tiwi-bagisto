<?php

use B2B\PriceRequest\Http\Controllers\Admin\PriceRequestController;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => ['web', 'admin'], 'prefix' => config('app.admin_url')], function () {
    Route::post('/price_request/{product_id}/set', [PriceRequestController::class, 'set_request_status'])->name("admin.price_request.set");
});