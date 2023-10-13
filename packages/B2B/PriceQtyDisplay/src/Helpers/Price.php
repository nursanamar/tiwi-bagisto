<?php

namespace B2B\PriceQtyDisplay\Helpers;

class Price
{

    public function getQtyPrices($product)
    {
        $prices = $product->customer_group_prices()->where("customer_group_id", null)->get();

        $result = [];

        foreach ($prices as $price) {
            $result[] = [
                "formated" => core()->currency($price->value),
                "value" => $price->value,
                "qty" => $price->qty
            ];
        }

        return $result;
    }
}
