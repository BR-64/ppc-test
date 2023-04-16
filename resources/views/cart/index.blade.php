<x-app-layout>
    <div class="container lg:w-2/3 xl:w-2/3 mx-auto blacktext">
        <h1 class="pagehead">Your Cart Items</h1>

        <div x-data="{
            cartItems: {{
                json_encode(
                    $products->map(fn($product) => [
                        'id' => $product->id,
                        // 'slug' => $product->slug,
                        'image' => $product->image,
                        'title' => $product->item_code,
                        'price' => $product->retail_price,
                        'dimension' => $product->wlh,
                        'quantity' => $cartItems[$product->id]['quantity'],
                        'href' => route('product.view', $product->item_code),
                        'removeUrl' => route('cart.remove', $product),
                        'updateQuantityUrl' => route('cart.update-quantity', $product),
                        'type'=>$product->pre_order,
                    ])
                )
            }},
            get cartTotal() {
                return this.cartItems.reduce((accum, next) => accum + next.price * next.quantity, 0).toFixed(2)
            },
        }" class="bg-white p-4 rounded-lg shadow">
            <!-- Product Items -->
            <template x-if="cartItems.length">
                <div>
<!-- Product Item -->
<h2>In Stock</h2>
                    <template x-for="product of cartItems" :key="product.id">
{{-- Normal Items --}}
                        <template x-if="product.type == 0">  
                            <div x-data="productItem(product)">
                                <div
                                    class=" w-full flex flex-row items-top gap-4 flex-1">
                                    <div class="piccol">
                                        <a :href="product.href"
                                        class=" flex items-top justify-center overflow-hidden">
                                            <img :src="product.image" class=" piccol object-cover" alt=""/>
                                        </a>
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <div class="flex justify-between mb-3">
                                            <div class="prodcol">

                                                <div class="text-sm">
                                                    <h3 class="nomar" x-text="product.title"></h3>
                                                    <p class="nomar"x-text="product.dimension"></p>
                                                </div>
                                            </div>
                                            <div class="calcol flex flex-col justify-between items-end">
                                                <div class="text-sm font-semibold items-end">
                                                    THB 
                                                    <span x-text="product.price">
                                                    </span>
                                                    </div>
                                                <div class="flex items-center">
                                                    <p class="text-sm nomar">Qty:</p>
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        x-model="product.quantity"
                                                        @change="changeQuantity()"
                                                        class="qtycart  border-gray-200" 
                                                    />
                                                </div>
                                                    <i class="fa-solid fa-trash binicon" 
                                                    @click.prevent="removeItemFromCart()"></i>
                                                {{-- <div class="qtyinput">  
                                                    <div class="incre_sm">
                                                        <button onclick="decrenum()"><i class="fa-regular fa-square-minus"></i></button>
                                                    </div>  
                                                    <input
                                                    id='qty_sm'
                                                    type="number"
                                                    name="quantity"
                                                    x-ref="quantityEl"
                                                    x-model="product.quantity"
                                                    @change="changeQuantity()"
                                                    class="qtyinputbox focus:outline-none rounded"/>       
                                                    <div class="incre_sm">
                                                        <button  onclick="increnum()"><i class="fa-regular fa-square-plus"></i></button>
                                                    </div>
                                                    <a
                                                        href="#"
                                                        @click.prevent="removeItemFromCart()"
                                                        class="binicon text-purple-600 hover:text-purple-500"
                                                    ><i class="fa-solid fa-trash"></i></a
                                                    >
                                                </div> --}}
                                            </div>
                                                
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5"/>
                            </div>
                        </template>
                    </template>
                    
                    {{-- Pre-order items  --}}
                    <hr class="my-5"/>
<h2>Pre-Order</h2>
<p class='notice'>* Pre-Order item will be ready to ship within XX Days after payment</p>
<template x-for="product of cartItems" :key="product.id">

                        <template x-if="product.type == 1"> 
                            <div x-data="productItem(product)">
                                <div
                                    class=" w-full flex flex-row items-top gap-4 flex-1">
                                    <div class="piccol">
                                        <a :href="product.href"
                                        class=" flex items-top justify-center overflow-hidden">
                                            <img :src="product.image" class=" piccol object-cover" alt=""/>
                                        </a>
                                    </div>
                                    <div class="flex flex-col justify-between flex-1">
                                        <div class="flex justify-between mb-3">
                                            <div class="prodcol">

                                                <div class="text-sm">
                                                    <h3 class="nomar" x-text="product.title"></h3>
                                                    <p class="nomar"x-text="product.dimension"></p>
                                                </div>
                                            </div>
                                            <div class="calcol flex flex-col justify-between items-end">
                                                <div class="text-sm font-semibold items-end">
                                                    THB 
                                                    <span x-text="product.price">
                                                    </span>
                                                    </div>
                                                <div class="flex items-center">
                                                    <p class="text-sm nomar">Qty:</p>
                                                    <input
                                                        type="number"
                                                        min="1"
                                                        x-model="product.quantity"
                                                        @change="changeQuantity()"
                                                        class="qtycart  border-gray-200" 
                                                    />
                                                </div>
                                                <i class="fa-solid fa-trash binicon" 
                                                @click.prevent="removeItemFromCart()"></i>
                                                
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-5"/>
                            </div>
                        </template>
                    </template>
<!-- subtotal -->

                    <div class="border-t border-gray-300 pt-4">
                        <div class="flex justify-between">
                            <span class="font-semibold">Subtotal 
                            <span class="notice">(included tax)
                            </span>
                        </span>
                                
                            <span id="cartTotal" class="text-xl" x-text="`THB ${cartTotal}`"></span>
                        </div>
                        <p class="text-gray-500 mb-6">
                            Shipping calculated at checkout.
                        </p>

                        <form action="{{route('cart.checkout')}}" method="post">
                        {{-- <form action="{{route('getjson')}}" method="post"> --}}
                            @csrf
                            <button type="submit" class="btn-primary w-full py-3 text-lg">
                            {{-- <button type="submit" class="tcolor1 w-full py-3 text-lg"> --}}
                                Proceed to Checkout
                            </button>

                        </form>
{{-- 
                        <form method="POST" action="/checkout">
                            <script type="text/javascript"
                            src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                            data-apikey="pkey_test_20923L43S4RABbtwVoMIzsopFCYzyZnyMwFsn"
                            data-amount="1.00"
                            data-payment-methods="card"
                            data-name="Your Shop Name"
                            data-mid="451320492949001">
                            </script>
                            <button> K chekout</button>
                        </form> --}}
{{-- 
                        <form method="POST" action="/checkout">
                            <script type="text/javascript"
                                src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                                data-amount="145.00"
                                data-payment-methods="qr"
                                data-order-id="order1234">
                            </script>
                            <button>k qr</button>
                            </form> --}}

                            {{-- <form method="POST" action="checkout/success">
                                <script type="text/javascript"
                                    src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                    data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                                    data-amount="174.00"
                                    data-currency="THB"
                                    data-payment-methods="card"
                                    data-name="test prempracha"
                                    data-mid="451320492949001">
                                </script>
                                <button>test</button>
                                </form> --}}


                                <form method="POST" action="{{route('getjson')}}">
                                    @csrf
                                    <script type="text/javascript"
                                        src="https://dev-kpaymentgateway.kasikornbank.com/ui/v2/kpayment.min.js"
                                        data-apikey="pkey_test_21633PhMyUk08kpleKc3LN6EsuSc4vV9KY3fC"
                                        data-amount="1900.00"
                                        data-currency="THB"
                                        data-payment-methods="card"
                                        data-name="Test shop"
                                        description="product21"
                                        reference_order="test124"
                                        data-mid="451320492949001">
                                    </script>
                                        <button>test pay</button>
                                    </form>

                        <br>
                        <form action="{{route('cart.quotation')}}" method="post">
                            @csrf
                            <button type="submit" class="btn-secondary w-full py-3 text-lg">
                                Ask for Quotation
                            </button>
                        </form>
                    </div>
                </div>
                
            </template>

<!--/ No Items -->
            <template x-if="!cartItems.length">
                <div class="text-center py-8 text-gray-500">
                    You don't have any items in cart
                </div>
            </template>

        </div>
    </div>
    <div class="footspace"></div>

    <script>
        var i = 1;
        function increnum() {
            document.getElementById('qty_sm').value = ++i;
        }
        function decrenum() {
            document.getElementById('qty_sm').value = --i;
        }
    </script>

</x-app-layout>

