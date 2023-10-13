@inject ('priceHelper', 'B2B\PriceQtyDisplay\Helpers\Price')

@php
    $qtyPrices = $priceHelper->getQtyPrices($product);
@endphp

<div class="main">
    <!--Prices based on qty -->
    @if ($qtyPrices)
        <div>
            <table class="table">
                <thead>
                    <td>Qty</td>
                    <td>Price</td>
                </thead>
                <tbody>
                    @foreach ($qtyPrices as $qtyPrice)
                        <tr>
                            <td>{{ $qtyPrice['qty'] }}</td>
                            <td>{{ $qtyPrice['formated'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>