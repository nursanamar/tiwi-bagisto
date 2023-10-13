<?php

Route::group([
        'prefix'     => 'priceqtydisplay',
        'middleware' => ['web', 'theme', 'locale', 'currency']
    ], function () {

        Route::get('/', 'B2B\PriceQtyDisplay\Http\Controllers\Shop\PriceQtyDisplayController@index')->defaults('_config', [
            'view' => 'priceqtydisplay::shop.index',
        ])->name('shop.priceqtydisplay.index');

});