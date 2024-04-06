<style>
    .spinner {
        width: 75px;
        height: 75px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(255, 255, 255, 0.05);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
    100% {
        transform: rotate(360deg);
    }
    }
</style>
<x-admin::layouts>
    <x-slot:title>
        Create New Order
    </x-slot:title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" integrity="sha512-PgQMlq+nqFLV4ylk1gwUOgm6CtIIXkKwaIHp/PAIWHzig/lKZSEGKEysh0TCVbHJXCLN7WetD8TFecIky75ZfQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="grid">
        <div class="flex gap-[16px] justify-between items-center max-sm:flex-wrap">
            <div class="flex gap-[10px] items-center">
                <p class="text-[20px] text-gray-800 dark:text-white font-bold leading-[24px]">
                    Create New Order
                </p>
            </div>
        </div>
        <v-order />
    </div>
@pushOnce('scripts')
    <script type="text/x-template" id="v-order-template">
        <div>
            <div class="grid gap-[10px]">
                <v-order-customer
                    @onChange="setCustomer($event)"
                />
    
                <v-order-items
                    :items="cart.items"
                    :total="cart.sub_total"
                    @onUpdateProduct="updateProductQty($event)"
                    @onAddProducts="addProduct($event,1)"
                    @onDeleteProduct="deleteProduct($event)"
                    v-if="customer"
                />
    
                <div class="grid grid-cols-2 gap-[10px]">
                    <v-order-address v-if="address" :address="address" />
                    <v-order-summary v-if="cart" :cart="cart" />
                </div>
    
                <div class="grid grid-cols-2 gap-[10px]">
                    <v-order-shipping 
                        v-if="address" 
                        :shippings="shippings" 
                        :selected="cart.selected_shipping_rate_method"
                        @onChange="setShippingMethod($event)"
                    />
    
                    <v-order-payment 
                        v-if="address"
                        :payments="payments"
                        :selected="cart.payment_method"
                        @onChange="setPaymentMethod($event)"
                    />
                </div>
    
                <div v-if="readyToCheckout" class="flex justify-end">
                    <div
                        class="primary-button"
                        @click="checkout"
                    >
                        Create Order
                    </div>
                </div>
            </div>
            <v-order-loading v-if="loading" />
        </div>
    </script>

    <script type="text/x-template" id="v-order-loading-template">
        <div 
            class="flex justify-center items-center w-full fixed top-0 left-0 right-0 z-99" 
            style="height: 100%;background: rgba(0,0,0,.4);"
        >
            <div>
                <span class="spinner"></span>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-order-customer-template">
        <div class="grid gap-[10px]">
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Customer
                        </p>
                    </div>
                </div>
                <div v-if="selected">
                    <div class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800">
                            <div class="flex gap-[10px]">
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        @{{ selected.first_name }} @{{ selected.last_name }}
                                    </p>
    
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ selected.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-x-[4px] items-center">
                                <div
                                    class="secondary-button"
                                    @click="changeCustomer()"
                                >
                                   Change Customer
                                </div>
                            </div>
                        </div>
                </div>
                
                <div v-else>
                    <div class="flex gap-[10px] justify-between p-[16px] border-b-[1px] border-slate-300 dark:border-gray-800">
                        <div class="relative w-full">
                            <input
                                type="text"
                                class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block w-full ltr:pl-[12px] rtl:pr-[12px] ltr:pr-[40px] rtl:pl-[40px] py-[5px] leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400"
                                placeholder="Search by name"
                                v-model.lazy="searchTerm"
                                v-debounce="500"
                            />
        
                            <span class="icon-search text-[22px] absolute ltr:right-[12px] rtl:left-[12px] top-[6px] flex items-center pointer-events-none"></span>
                        </div>
                    </div>
    
                    <div class="grid" v-if="searchResults.length">
                        <div
                            class="flex gap-[10px] justify-between px-[16px] py-[24px] border-b-[1px] border-slate-300 dark:border-gray-800"
                            v-for="customer in searchResults"
                        >
                            <div class="flex gap-[10px]">
                                <div class="grid gap-[6px] place-content-start">
                                    <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                        @{{ customer.first_name }} @{{ customer.last_name }}
                                    </p>
    
                                    <p class="text-gray-600 dark:text-gray-300">
                                        @{{ customer.email }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-x-[4px] items-center">
                                <div
                                    class="secondary-button"
                                    @click="selectCustomer(customer)"
                                >
                                   Select Customer
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>


    <script type="text/x-template" id="v-order-items-template">
        <div class="grid gap-[10px]">
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Products
                        </p>
                    </div>
                    
                    <!-- Add Button -->
                    <div class="flex gap-x-[4px] items-center">
                        <div
                            class="secondary-button"
                            @click="$refs.productSearch.openDrawer()"
                        >
                            @lang('admin::app.catalog.products.edit.links.add-btn')
                        </div>
                    </div>
                </div>
                <div class="flex gap-[20px] justify-between mb-[10px] p-[16px]">
                    <table class="table-fixed w-full p-[16px] border-collapse border border-slate-400">
                        <thead>
                            <tr class="font-bold">
                                <td class="p-[10px] border border-slate-300 pb-[10px]">Product</td>
                                <td class="p-[10px] border border-slate-300">Price</td>
                                <td class="p-[10px] border border-slate-300">Quantity</td>
                                <td class="p-[10px] border border-slate-300">Total</td>
                                <td class="p-[10px] border border-slate-300"></td>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="product in items">
                                <tr>
                                    <td class="border border-slate-300 px-[10px] py-[5px]" style="width: 30%">
                                        <div class="flex gap-[10px]">
                                            <!-- Image -->
                                            <img class="min-h-[65px] min-w-[65px] max-h-[65px] max-w-[65px] rounded-[4px]" :src="product.base_image.medium_image_url">
                                            <!-- Details -->
                                            <div class="grid gap-[6px] place-content-start">
                                                <p class="text-[16x] text-gray-800 dark:text-white font-semibold">
                                                    @{{ product.name }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="border border-slate-300 px-[10px] py-[5px]" style="width: 10%">
                                        <p class="text-gray-800 font-semibold dark:text-white">
                                            @{{ $admin.formatPrice(product.price) }}
                                        </p>
                                    </td>
                                    <td class="border border-slate-300 px-[10px] py-[5px]" style="width: 20%">
                                        <div class="p-[10px]">
                                            <v-order-quantity-changer
                                                name=""
                                                @onChange="updateProductQty(product.id,$event)"
                                                :value="product.quantity"
                                            />
                                        </div>
                                    </td>
                                    <td class="border border-slate-300 px-[10px] py-[5px]" style="width: 20%">
                                        <p class="text-gray-800 font-semibold dark:text-white">
                                            @{{ $admin.formatPrice(product.total) }}
                                        </p>
                                    </td>
                                    <td class="border border-slate-300 px-[10px] py-[5px]" style="width: 10%">
                                        <button @click="deleteProduct(product.id)" type="button">
                                            <span class="icon-delete cursor-pointer rounded-[6px] p-[6px] text-[24px] transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center" title="Delete"></span>
                                        </button>
                                    </td>
                                </tr>
                            </template>        
                            <tr>
                                <td colspan="3" class="border border-slate-300 px-[10px] py-[5px] mt-[14px]">
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        Sub Total
                                    </p>
                                </td>
                                <td class="border border-slate-300 px-[10px] py-[5px] mt-[14px]">
                                    <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                                        @{{ $admin.formatPrice(total) }}
                                    </p>
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <x-admin::products.search
            ref="productSearch"
            ::added-product-ids="addedProductIds"
            @onProductAdded="addProducts($event)"
        ></x-admin::products.search>
    </script>

    <script type="text/x-template" id="v-order-quantity-changer-template">
        <div class="flex gap-[10px]">
            <input type="number" :disabled="!isEditing" class="bg-white dark:bg-gray-900 border dark:border-gray-800 rounded-lg block ltr:pl-[12px] rtl:pr-[12px] ltr:pr-[40px] rtl:pl-[40px] py-[5px] leading-6 text-gray-600 dark:text-gray-300 transition-all hover:border-gray-400" v-model="quantity" />
            <div>
                <div
                    class="secondary-button "
                    @click="edit()"
                    v-if="!isEditing"
                >
                    Edit
                </div>
                <div
                    class="secondary-button"
                    @click="update()"
                    v-if="isEditing"
                >
                    Save
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-order-address-template">
        <div class="grid gap-[10px]">
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Address
                        </p>
                    </div>
                </div>
                <div v-if="address" class="flex gap-[2px] justify-between mb-[10px] p-[16px]">
                    <div>
                        <div class="grid gap-y-[10px]">
                            <p class="text-gray-800 font-semibold dark:text-white">@{{ address.first_name }} @{{ address.last_name }}</p>
                            <p class="text-gray-600 dark:text-gray-300"> @{{ address.address1 }}, @{{ address.city }}, @{{ address.state }} (@{{ address.postcode }}) </p>
                            <p class="text-gray-600 dark:text-gray-300"> Phone - @{{ address.phone }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-order-summary-template">
        <div class="grid gap-[10px]">
            <div v-if="cart.grand_total" class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Summary
                        </p>
                    </div>
                </div>
                <div class="flex gap-[2px] justify-between m-[1px] ">
                    <div class="flex w-full gap-[10px] justify-between p-[16px]">
                        <div class="flex flex-col gap-y-[6px]">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold"> Sub Total </p>
                            <p class="text-gray-600 dark:text-gray-300"> Tax </p>
                            <p class="text-gray-600 dark:text-gray-300"> Shipping and Handling</p>
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold"> Grand Total </p>
                        </div>
                        <div class="flex flex-col gap-y-[6px]">
                            <p class="text-gray-600 dark:text-gray-300 font-semibold">@{{ $admin.formatPrice(cart.sub_total) }}</p>
                            <p class="text-gray-600 dark:text-gray-300">@{{ $admin.formatPrice(cart.tax_total) }}</p>
                            <p class="text-gray-600 dark:text-gray-300">@{{ cart.selected_shipping_rate }},00</p>
                            <p class="text-[16px] text-gray-800 dark:text-white font-semibold">@{{ $admin.formatPrice(cart.grand_total) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>


    <script type="text/x-template" id="v-order-shipping-template">
        <div class="grid gap-[10px]">
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px] pb-[0px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Shipping methods
                        </p>
                    </div>
                </div>
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px]">
                    <div>
                        <template v-for="shipping in shippings">
                            <p class="text-gray-800 font-semibold leading-6 dark:text-white">@{{ shipping.carrier_title }}</p>
                                <template v-for="rate in shipping.rates">
                                    <div class="flex items-center mb-4">
                                        <input @change="onChangeMethod(rate.method)" type="radio" id="@{{ rate.method }}" name="shipping" value="@{{ rate.method }}" class="h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-300" aria-labelledby="country-option-1" aria-describedby="country-option-1" checked="">
                                        <label for="@{{ rate.method }}" class="text-sm font-medium text-gray-900 ml-2 block">
                                            @{{ rate.carrier_title }}
                                        </label>
                                    </div>
                                </template>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="v-order-payment-template">
        <div class="grid gap-[10px]">
            <div class="relative bg-white dark:bg-gray-900 rounded-[4px] box-shadow">
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px] pb-[0px]">
                    <div class="flex flex-col gap-[8px]">
                        <p class="text-[16px] text-gray-800 dark:text-white font-semibold">
                            Payments methods
                        </p>
                    </div>
                </div>
                <div class="flex gap-[20px] justify-between mb-[1px] p-[16px]">
                    <div>
                        <template v-for="payment in payments">
                            <div class="flex items-center mb-4">
                                <input @change="onChangeMethod(payment.method)" type="radio" id="@{{ payment.method }}" name="payment" value="@{{ payment.method }}" class="h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-300" aria-labelledby="country-option-1" aria-describedby="country-option-1" checked="">
                                <label for="@{{ payment.method }}" class="text-sm font-medium text-gray-900 ml-2 block">
                                    @{{ payment.method_title }}
                                </label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="module" type="text/javascript">
        app.component('v-order',{
            template: '#v-order-template',
            data() {
                return {
                    customer: null,
                    address: null,
                    shippings: [],
                    selected_shipping: null,
                    payments: [],
                    selected_payment: null,
                    cart: {
                        items: []
                    },
                    readyToCheckout: false,
                    loading: false,
                }
            },
            methods: {
                setCustomer(customer){
                    if (customer) {
                        let url = "{{ route('admin.adminordercreation.customer.login') }}"
                        this.$axios.get(`${url}?id=${customer.id}`)
                        .then(async res => {
                            this.loadCart();
                        })
                    }
                    this.customer = customer;
                },
                async setCheckoutAddress(address){
                    let url = "{{ route('shop.checkout.onepage.addresses.store') }}";
                    await this.$axios.post(url,{
                        billing: {
                            ...address,
                            address1: [address.address1],
                            address_id: address.id,
                            use_for_shipping: true
                        },
                        shipping: {
                            address_id: address.id,
                            address1: [address.address1],
                        }
                    }).then(res => {
                        if (res.data.data) {
                            const {shippingMethods} = res.data.data
                            this.shippings = shippingMethods;
                        }
                    })
                    this.address = address
                },
                async getCustomerAddress(){
                    let url = "{{ route('api.shop.customers.account.addresses.index') }}";
                    let customerAddress = await this.$axios.get(`${url}`)
                    .then(res => {
                        const address = res.data.data
                        if (address && address.length) {
                            const defaultAddress = address.find(item => item.default_address)
                            return defaultAddress;
                        }
                        return null;
                    })

                    return customerAddress;
                },
                loadCart() {
                    this.$axios.get("{{ url('api/checkout/cart') }}")
                    .then(async (response) => {
                        if (response.data.data) {
                            this.cart = response.data.data;
                        }

                        const address = await this.getCustomerAddress();
                        await this.setCheckoutAddress(address);
                    });
                },
                
                addProduct(product_ids, qty) {
                    product_ids.forEach(id => {
                        let isExist = this.cart.items.find(item => item.id === id);
                        if (isExist) {
                            console.log(isExist,"already exist");
                            this.updateProductQty(id, isExist.quantity + 1);        
                        }else{
                            this.addProductToCard(id,qty);
                        }
                    })
                },
                addProductToCard(id,qty){
                    this.$axios.post("{{ url('api/checkout/cart') }}",{
                        product_id: id,
                        quantity: qty
                    })
                    .then(response => {
                        this.loadCart();
                    });
                },
                updateProductQty({id, qty}){
                    const data = {};
                    
                    data[id] = qty;

                    this.$axios.put("{{ url('api/checkout/cart') }}",{
                        qty: data
                    })
                    .then(response => {
                        this.loadCart();
                    });
                },
                deleteProduct(id){
                    this.updateProductQty({id, qty: 0})
                },
                setShippingMethod(method){
                    let url = "{{ route('shop.checkout.onepage.shipping_methods.store') }}";
                    this.$axios.post(url,{
                        shipping_method: method
                    }).then(res => {
                        console.log(res);
                        this.selected_shipping = method;
                        this.payments = res.data.payment_methods
                        this.loadCart();
                    })
                },
                setPaymentMethod(method){
                    let url = "{{ route('shop.checkout.onepage.payment_methods.store') }}";
                    const payment = this.payments.find(item => item.method === method);

                    this.$axios.post(url,{
                        payment: payment
                    }).then(res => {
                        this.loadCart();
                        this.readyToCheckout = true;
                    })
                },
                checkout(){
                    if (this.readyToCheckout) {
                        let url = "{{ route('shop.checkout.onepage.orders.store') }}";
                        let orderUrl = "{{ route('admin.sales.orders.index') }}"
                        this.loading = true;
                        this.$axios.post(url,{
                        }).then(res => {
                            console.log(res);
                            window.location.href = orderUrl
                        })
                    }
                }
            }
        })

        app.component('v-order-customer',{
            template: '#v-order-customer-template',
            data() {
                return {
                    searchTerm: '',
                    selected: null,
                    searchResults: []
                }
            },
            watch: {
                searchTerm: function(newVal, oldVal) {
                    this.search();
                }
            },
            methods: {
                search(){
                    let url = "{{ route('admin.customers.customers.search') }}"

                    if (this.searchTerm.length < 3) {
                        return;
                    }

                    this.$axios.get(`${url}`, {
                            params: {
                                query: this.searchTerm
                            }
                        })
                        .then((response) => {
                            this.searchResults = response.data.data;
                        })
                        .catch(function (error) {
                        })
                },
                
                selectCustomer(customer){
                    this.selected = customer;
                    this.$emit('onChange',this.selected)
                },

                changeCustomer() {
                    this.selected = null;
                    this.searchTerm = '';
                    this.searchResults = [];

                    this.$emit('onChange',this.selected)
                }
            }
        })

        app.component('v-order-items', {
            template: '#v-order-items-template',
            props: {
                items: {
                    default: []
                },
                total: {
                    default: 0
                }
            },
            methods: {
                addProducts(selectedProducts) {
                    let ids = selectedProducts.map(product => product.id);
                    this.$emit("onAddProducts",ids);
                },
                updateProductQty(product_id, qty) {
                    this.$emit("onUpdateProduct", {id: product_id, qty: qty})
                },
                deleteProduct(product_id){
                    this.$emit("onDeleteProduct",product_id);
                }
            }
        });

        app.component('v-order-quantity-changer', {
            template: "#v-order-quantity-changer-template",
            props:['name', 'value'],

            data() {
                return  {
                    quantity: this.value,
                    isEditing: false
                }
            },

            watch: {
                value() {
                    this.quantity = this.value;
                },
            },

            methods: {
                update() {
                    this.isEditing = false;
                    this.$emit('onChange', this.quantity);
                },
                edit(){
                    this.isEditing = true;
                }
            }
        })

        app.component('v-order-address', {
            template: '#v-order-address-template',
            props: {
                address: {
                    default: null
                }
            }
        })

        app.component('v-order-summary',{
            template: "#v-order-summary-template",
            props: {
                cart: {
                    default: null
                }
            }
        })


        app.component('v-order-shipping',{
            template: "#v-order-shipping-template",
            props: {
                shippings: {
                    default: []
                },
            },
            data(){
                return {
                    selected: ""
                }
            },
            methods: {
                onChangeMethod(method){
                    this.selected = method
                    this.$emit('onChange',method);
                }
            }
        })

        app.component('v-order-payment',{
            template: "#v-order-payment-template",
            props: {
                payments: {
                    default: []
                },
            },
            data(){
                return {
                    selected: ""
                }
            },
            methods: {
                onChangeMethod(method){
                    this.selected = method
                    this.$emit('onChange',method);
                }
            }
        })


        app.component('v-order-loading',{
            template: "#v-order-loading-template",
        })

    </script>
@endPushOnce
</x-admin::layouts>