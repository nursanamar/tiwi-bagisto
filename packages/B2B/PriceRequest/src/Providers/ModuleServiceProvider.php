<?php

namespace B2B\PriceRequest\Providers;

use B2B\PriceRequest\Models\PriceRequest;
use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        PriceRequest::class
    ];
}