<?php

Route::group([
        'prefix'        => 'admin/priceqtydisplay',
        'middleware'    => ['web', 'admin']
    ], function () {

        Route::get('', 'B2B\PriceQtyDisplay\Http\Controllers\Admin\PriceQtyDisplayController@index')->defaults('_config', [
            'view' => 'priceqtydisplay::admin.index',
        ])->name('admin.priceqtydisplay.index');

});