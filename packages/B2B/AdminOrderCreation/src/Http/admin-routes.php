<?php

Route::group([
    'prefix'        => 'admin/adminordercreation',
    'middleware'    => ['web', 'admin']
], function () {

    Route::get('', 'B2B\AdminOrderCreation\Http\Controllers\Admin\AdminOrderCreationController@index')->defaults('_config', [
        'view' => 'adminordercreation::admin.index',
    ])->name('admin.adminordercreation.index');

    Route::get('customer-login', 'B2B\AdminOrderCreation\Http\Controllers\Admin\AdminOrderCreationController@get_customer_token')
    ->name('admin.adminordercreation.customer.login');
});
