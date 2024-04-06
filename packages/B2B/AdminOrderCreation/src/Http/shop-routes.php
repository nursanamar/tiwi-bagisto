<?php

Route::group([
        'prefix'     => 'adminordercreation',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'B2B\AdminOrderCreation\Http\Controllers\Shop\AdminOrderCreationController@index')->defaults('_config', [
            'view' => 'adminordercreation::shop.index',
        ])->name('shop.adminordercreation.index');

});