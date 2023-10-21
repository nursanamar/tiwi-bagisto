<?php 

namespace B2B\PriceRequest\Helpers;

use B2B\PriceRequest\Models\PriceRequest;

class Price {
    public static function is_need_request($product) {
        $price_request = PriceRequest::where("product_id",$product->id)->first();

        if ($price_request) {
            return true;
        }else{
            return false;
        }
    }
}