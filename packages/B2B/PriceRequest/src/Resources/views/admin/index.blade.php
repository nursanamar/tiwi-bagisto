@inject ('priceHelper', 'B2B\PriceRequest\Helpers\Price')

@php
    $price_status = $priceHelper->is_need_request($product);
@endphp

<div class="relative p-[16px] bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
    <p class="text-[16px] text-gray-800 dark:text-white font-semibold mb-[16px]">
        Price Request
    </p>
    <div class="mb-[10px]">
        <label class="block leading-[24px] text-[12px] text-gray-800 dark:text-white font-medium">
            Yes
        </label>
        <label class="relative inline-flex items-center cursor-pointer">
            <input onchange="setStatus(this)" type="checkbox" name="price_request" id="price_request" class="sr-only peer" {{ $price_status ? "checked" : "" }}>
            <label for="price_request" class="rounded-full w-[36px] h-[20px] bg-gray-200 cursor-pointer peer-focus:ring-blue-300 after:bg-white dark:after:bg-white after:border-gray-300 dark:after:border-white peer-checked:bg-blue-600 dark:peer-checked:bg-gray-950 peer peer-checked:after:border-white peer-checked:after:ltr:translate-x-full peer-checked:after:rtl:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:ltr:left-[2px] after:rtl:right-[2px] peer-focus:outline-none after:border after:rounded-full after:h-[16px] after:w-[16px] after:transition-all dark:bg-gray-800">
            </label>
        </label>
    </div>
</div>
@pushOnce('scripts')
<script>
    
    function setStatus(e) {
        console.log(e.checked)

        const url = "{{ route('admin.price_request.set',['product_id' => $product->id]) }}"
        const payload = {status : e.checked}

        window.axios.post(url,payload).then(console.log);
    }
</script>
@endPushOnce

