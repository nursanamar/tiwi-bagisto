<?php

namespace B2B\PriceRequest\Contracts;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface PriceRequest
{
    public function product() : HasOne;
}