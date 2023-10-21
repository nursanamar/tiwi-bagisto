<?php

namespace B2B\PriceRequest\Models;

use Illuminate\Database\Eloquent\Model;
use B2B\PriceRequest\Contracts\PriceRequest as PriceRequestContract;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Webkul\Product\Models\Product;

class PriceRequest extends Model implements PriceRequestContract
{
    protected $fillable = [
        "product_id"
    ];

    protected $table = 'price_request';

    public function product() : HasOne {
        return $this->hasOne(Product::class);
    }
}