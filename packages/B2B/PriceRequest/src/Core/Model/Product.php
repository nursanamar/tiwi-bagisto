<?php

namespace B2B\PriceRequest\Core\Model;

use B2B\PriceRequest\Helpers\Price;
use Webkul\Product\Models\Product as ModelsProduct;

class Product extends ModelsProduct {
    public function is_need_request() : bool {
        return Price::is_need_request($this);
    }
}